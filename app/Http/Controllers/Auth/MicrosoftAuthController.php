<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Services\MicrosoftGraphService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MicrosoftAuthController extends Controller
{
    public function __construct(
        protected MicrosoftGraphService $graphService
    ) {}

    /**
     * Redirect user to Microsoft Entra / Azure OAuth authorization URL.
     */
    public function redirect(Request $request): RedirectResponse
    {
        if (! $this->graphService->isConfigured()) {
            return redirect()->route('login')->with('error', 'Microsoft Authentication is not configured in environment settings.');
        }

        $state = Str::random(40);
        $request->session()->put('microsoft_oauth_state', $state);

        return redirect()->away($this->graphService->getAuthorizationUrl($state));
    }

    /**
     * Handle the OAuth callback from Microsoft.
     */
    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            $errorDesc = $request->input('error_description', 'Sign-in was cancelled or denied.');
            Log::warning('Microsoft OAuth Error Callback: '.$errorDesc);

            return redirect()->route('login')->with('error', 'Microsoft sign-in cancelled: '.$errorDesc);
        }

        $expectedState = $request->session()->pull('microsoft_oauth_state');
        $receivedState = $request->query('state');

        if (empty($expectedState) || empty($receivedState) || ! hash_equals($expectedState, $receivedState)) {
            return redirect()->route('login')->with('error', 'Invalid login session or security state expired. Please try again.');
        }

        $code = $request->query('code');
        if (empty($code)) {
            return redirect()->route('login')->with('error', 'No authorization code returned from Microsoft.');
        }

        $userToken = $this->graphService->getUserTokenFromCode($code);
        if (! $userToken) {
            return redirect()->route('login')->with('error', 'Unable to exchange authorization code with Microsoft. Please contact administrator.');
        }

        $profile = $this->graphService->getUserProfile($userToken);
        if (! $profile || empty($profile['email'])) {
            return redirect()->route('login')->with('error', 'Unable to read your profile or email address from Microsoft.');
        }

        $email = strtolower($profile['email']);
        $azureId = $profile['id'];

        // Find existing user by azure_id or email
        $user = User::where('azure_id', $azureId)
            ->orWhereRaw('LOWER(email) = ?', [$email])
            ->first();

        if ($user) {
            // User already registered in this system
            $user->update([
                'azure_id' => $azureId,
                'auth_provider' => 'microsoft',
            ]);
        } else {
            // User belongs to Azure Tenant but not yet added
            // Auto-provision user with assigned default active company
            $defaultCompany = Company::where('is_active', true)->first();

            $user = User::create([
                'name' => $profile['name'] ?: 'Microsoft User',
                'email' => $email,
                'azure_id' => $azureId,
                'auth_provider' => 'microsoft',
                'company_id' => $defaultCompany?->id,
            ]);

            $user->assignRole('subsidiary_hr_manager');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard', absolute: false))
            ->with('success', "Signed in successfully via Microsoft 365 as {$user->name}.");
    }
}
