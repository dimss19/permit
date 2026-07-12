<!DOCTYPE html><html lang="id" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Login - Safety Permit INKA Madiun</title>
<!-- BEGIN: Scripts and Styles -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'inka-navy': '#111d33',
            'inka-light-gray': '#f4f6f9',
            'inka-text-muted': '#6c757d',
            'inka-border': '#dee2e6'
          }
        }
      }
    }
  </script>
<style data-purpose="custom-fonts">
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
<style data-purpose="layout-adjustments">
    /* Ensure the body takes full height for the sticky footer effect if content is short */
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      background-color: #f4f6f9;
    }
    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }
  </style>
<!-- END: Scripts and Styles -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" data-snapdom="injected-import"></head>
<body class="bg-inka-light-gray">
<!-- BEGIN: MainContent -->
<main>
<div class="w-full max-w-md">
<!-- Top Logo -->
<div class="flex justify-center mb-8">
<span class="h-10 flex items-center justify-center text-inka-navy font-bold">(logo)</span>
</div>
<!-- BEGIN: LoginCard -->
<div class="bg-white rounded-xl shadow-sm border border-inka-border p-8 md:p-10" data-purpose="login-card">
<!-- Header Text -->
<div class="text-center mb-8">
<h1 class="text-2xl font-bold text-inka-navy mb-2">Masuk ke Akun Anda</h1>
<p class="text-xs text-inka-text-muted">Safety Permit INKA Madiun — Sistem Monitoring K3 Kontraktor</p>
</div>
<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<form action="{{ route('login') }}" class="space-y-5" method="POST">
@csrf
<!-- Email Field -->
<div class="space-y-2">
<label class="block text-sm font-semibold text-gray-800" for="email">Email</label>
<div class="relative">
<span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</span>
<input class="block w-full pl-10 pr-3 py-2.5 border border-inka-border rounded-lg focus:ring-inka-navy focus:border-inka-navy text-sm placeholder-gray-400" id="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.gmail.com" type="email" required autofocus autocomplete="username">
</div>
<x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>
<!-- Password Field -->
<div class="space-y-2">
<label class="block text-sm font-semibold text-gray-800" for="password">Kata Sandi</label>
<div class="relative">
<span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</span>
<input class="block w-full pl-10 pr-10 py-2.5 border border-inka-border rounded-lg focus:ring-inka-navy focus:border-inka-navy text-sm placeholder-gray-400" id="password" name="password" placeholder="Masukkan kata sandi" type="password" required autocomplete="current-password">
</div>
<x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>
<!-- Remember Me & Forgot Password -->
<div class="flex items-center justify-between">
<div class="flex items-center">
<input class="h-4 w-4 text-inka-navy focus:ring-inka-navy border-gray-300 rounded" id="remember_me" name="remember" type="checkbox">
<label class="ml-2 block text-xs text-inka-text-muted" for="remember_me">
                Ingat Saya
              </label>
</div>
<div class="text-xs">
@if (Route::has('password.request'))
<a class="font-semibold text-inka-navy hover:underline" href="{{ route('password.request') }}">Lupa kata sandi?</a>
@endif
</div>
</div>
<!-- Submit Button -->
<div>
<button class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-bold text-white bg-inka-navy hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-inka-navy transition-colors" type="submit">
              Masuk
            </button>
</div>
</form>
<!-- Separator -->
<div class="mt-6 relative">
<div aria-hidden="true" class="absolute inset-0 flex items-center">
<div class="w-full border-t border-inka-border"></div>
</div>

</div>
<!-- Google Login -->
<div class="mt-6">

</div>
<!-- Registration Footer -->

</div>
<!-- END: LoginCard -->
</div>
</main>
<!-- END: MainContent -->
<!-- BEGIN: Footer -->
<footer class="bg-inka-navy text-white py-6 px-4 md:px-12 flex flex-col md:flex-row items-center justify-between">
<div class="flex items-center space-x-4 mb-4 md:mb-0">
<!-- Footer Icon -->
<div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
<span class="text-white font-bold text-xs">(logo)</span>
</div>
<div>
<h2 class="text-sm font-bold uppercase tracking-wide">PT INKA (Persero) MADIUN</h2>
<p class="text-[10px] text-gray-400 opacity-80 mt-1">© SAFETY PERMIT INKA MADIUN SHE PT. INKA (Persero) Madiun. All right reserved.</p>
</div>
</div>
<div class="flex items-center">
<span class="h-6 flex items-center text-white font-bold">(logo)</span>
</div>
</footer>
<!-- END: Footer -->






</body></html>
