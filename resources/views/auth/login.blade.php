<x-guest-layout>
    <div class="mb-4">
        <h2 class="text-xl font-bold text-slate-900">Sign In</h2>
        <p class="text-xs text-slate-500 mt-0.5">Access administrative dashboards and exit interview management.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    @if(session('error'))
        <div class="mb-3.5 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 flex items-start gap-2.5">
            <span class="text-sm leading-none">⚠️</span>
            <div class="leading-relaxed font-medium">{{ session('error') }}</div>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-3.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-700 flex items-start gap-2.5">
            <span class="text-sm leading-none">✅</span>
            <div class="leading-relaxed font-medium">{{ session('success') }}</div>
        </div>
    @endif

    <!-- Microsoft 365 SSO Primary Option -->
    <div class="mb-4">
        <a href="{{ route('auth.microsoft') }}" 
           class="w-full flex items-center justify-center gap-3 px-4 py-2.5 border border-slate-200 rounded-xl shadow-sm bg-white hover:bg-slate-50 hover:border-slate-300 text-sm font-semibold text-slate-700 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0078d4] group">
            <svg class="w-4 h-4 flex-shrink-0 group-hover:scale-105 transition-transform" viewBox="0 0 21 21">
                <rect x="1" y="1" width="9" height="9" fill="#f25022"/>
                <rect x="11" y="1" width="9" height="9" fill="#7fba00"/>
                <rect x="1" y="11" width="9" height="9" fill="#00a4ef"/>
                <rect x="11" y="11" width="9" height="9" fill="#ffb900"/>
            </svg>
            <span>Sign in with Microsoft 365</span>
        </a>
    </div>

    <!-- Divider -->
    <div class="relative my-4">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-slate-200"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase tracking-wider">
            <span class="px-3 bg-white text-slate-400 font-semibold text-[10px]">Or continue with email</span>
        </div>
    </div>

    <!-- Email & Password Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-3">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                Work Email Address
            </label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   placeholder="name@gsoptimize.lk"
                   class="block w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-[#8C0026] focus:ring-4 focus:ring-[#8C0026]/10 transition-all" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                Password
            </label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="current-password"
                   placeholder="••••••••"
                   class="block w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-[#8C0026] focus:ring-4 focus:ring-[#8C0026]/10 transition-all" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-0.5">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" 
                       type="checkbox" 
                       class="rounded border-slate-300 text-[#8C0026] shadow-sm focus:ring-[#8C0026] focus:ring-offset-0" 
                       name="remember">
                <span class="ms-2 text-xs font-medium text-slate-600">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-semibold text-[#8C0026] hover:text-[#520016] hover:underline transition-colors" 
                   href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-1.5">
            <button type="submit" 
                    style="background: linear-gradient(135deg, #8C0026 0%, #B30031 100%);"
                    class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold text-white shadow-md hover:shadow-lg hover:brightness-105 active:scale-[0.99] transition-all duration-150 cursor-pointer">
                <span>Sign In to Portal</span>
                <span class="text-sm leading-none">➔</span>
            </button>
        </div>
    </form>
</x-guest-layout>
