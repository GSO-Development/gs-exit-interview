<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MicrosoftGraphService
{
    protected ?string $tenantId;

    protected ?string $clientId;

    protected ?string $clientSecret;

    protected ?string $redirectUri;

    public function __construct()
    {
        $this->tenantId = config('services.azure.tenant_id');
        $this->clientId = config('services.azure.client_id');
        $this->clientSecret = config('services.azure.client_secret');
        $this->redirectUri = config('services.azure.redirect_uri');
    }

    /**
     * Check if Azure Graph credentials are fully configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->tenantId) && ! empty($this->clientId) && ! empty($this->clientSecret);
    }

    /**
     * Get an application-level access token via Client Credentials grant.
     */
    public function getAppToken(): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        return Cache::remember('azure_graph_app_token', 3300, function () {
            try {
                $response = Http::withoutVerifying()
                    ->asForm()
                    ->post("https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token", [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'scope' => 'https://graph.microsoft.com/.default',
                        'grant_type' => 'client_credentials',
                    ]);

                if ($response->successful()) {
                    return $response->json('access_token');
                }

                Log::error('Microsoft Graph Token Error: '.$response->body());

                return null;
            } catch (\Throwable $e) {
                Log::error('Microsoft Graph Token Exception: '.$e->getMessage());

                return null;
            }
        });
    }

    /**
     * Search tenant users via Microsoft Graph API.
     *
     * @return array<int, array<string, mixed>>
     */
    public function searchUsers(string $query = '', int $limit = 10): array
    {
        $token = $this->getAppToken();
        if (! $token) {
            return [];
        }

        try {
            $params = [
                '$top' => $limit,
                '$select' => 'id,displayName,mail,userPrincipalName,jobTitle,department',
            ];

            $query = trim($query);
            if (! empty($query)) {
                $escaped = str_replace("'", "''", $query);
                $params['$filter'] = "startsWith(displayName, '{$escaped}') or startsWith(mail, '{$escaped}') or startsWith(userPrincipalName, '{$escaped}')";
            }

            $response = Http::withoutVerifying()
                ->withToken($token)
                ->get('https://graph.microsoft.com/v1.0/users', $params);

            if (! $response->successful()) {
                Log::warning('Microsoft Graph search failed: '.$response->body());

                return [];
            }

            $rawUsers = $response->json('value') ?? [];
            $existingEmails = User::pluck('email')->map(fn ($e) => strtolower($e))->toArray();
            $existingAzureIds = User::whereNotNull('azure_id')->pluck('azure_id')->toArray();

            $results = [];
            foreach ($rawUsers as $u) {
                $email = strtolower($u['mail'] ?? $u['userPrincipalName'] ?? '');
                $azureId = $u['id'] ?? '';
                $isAdded = in_array($email, $existingEmails, true) || in_array($azureId, $existingAzureIds, true);

                $results[] = [
                    'id' => $azureId,
                    'name' => $u['displayName'] ?? '',
                    'email' => $email,
                    'userPrincipalName' => $u['userPrincipalName'] ?? '',
                    'job_title' => $u['jobTitle'] ?? 'Employee',
                    'department' => $u['department'] ?? '',
                    'is_already_added' => $isAdded,
                ];
            }

            return $results;
        } catch (\Throwable $e) {
            Log::error('Microsoft Graph search exception: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Generate the Microsoft OAuth 2.0 authorization URL.
     */
    public function getAuthorizationUrl(string $state): string
    {
        $params = http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'response_mode' => 'query',
            'scope' => 'openid profile email User.Read',
            'state' => $state,
            'prompt' => 'select_account',
        ]);

        return "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/authorize?{$params}";
    }

    /**
     * Exchange authorization code for user access token.
     */
    public function getUserTokenFromCode(string $code): ?string
    {
        try {
            $response = Http::withoutVerifying()
                ->asForm()
                ->post("https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token", [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $this->redirectUri,
                ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('Microsoft OAuth Token Exchange Error: '.$response->body());

            return null;
        } catch (\Throwable $e) {
            Log::error('Microsoft OAuth Token Exchange Exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get user profile from Graph /me endpoint using user access token.
     *
     * @return array<string, mixed>|null
     */
    public function getUserProfile(string $userAccessToken): ?array
    {
        try {
            $response = Http::withoutVerifying()
                ->withToken($userAccessToken)
                ->get('https://graph.microsoft.com/v1.0/me', [
                    '$select' => 'id,displayName,mail,userPrincipalName',
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'id' => $data['id'] ?? null,
                    'name' => $data['displayName'] ?? '',
                    'email' => strtolower($data['mail'] ?? $data['userPrincipalName'] ?? ''),
                    'userPrincipalName' => $data['userPrincipalName'] ?? '',
                ];
            }

            Log::error('Microsoft Graph /me Error: '.$response->body());

            return null;
        } catch (\Throwable $e) {
            Log::error('Microsoft Graph /me Exception: '.$e->getMessage());

            return null;
        }
    }
}
