<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        body {
            background-color: #0f0f1a;
            background-image:
                radial-gradient(ellipse 80% 60% at 20% -10%, rgba(99,102,241,.28) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 110%, rgba(139,92,246,.22) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 50% 50%, rgba(79,70,229,.06) 0%, transparent 70%);
        }

        /* Dot grid overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,.045) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            z-index: 0;
        }

        .glass-card {
            background: rgba(255,255,255,.03);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,.08);
            box-shadow:
                0 0 0 1px rgba(99,102,241,.12),
                0 32px 64px -12px rgba(0,0,0,.7),
                0 0 80px -20px rgba(99,102,241,.2);
        }

        /* Input focus glow */
        .field-wrap { position: relative; }
        .field-wrap input {
            transition: border-color .2s, box-shadow .2s;
        }
        .field-wrap input:focus {
            outline: none;
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(99,102,241,.18), 0 1px 3px rgba(0,0,0,.4);
        }

        /* Sign-in button */
        .btn-signin {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            box-shadow: 0 4px 24px -4px rgba(99,102,241,.55);
            transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
        }
        .btn-signin:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 32px -4px rgba(99,102,241,.65);
            filter: brightness(1.08);
        }
        .btn-signin:active:not(:disabled) {
            transform: translateY(0);
        }

        /* Logo mark */
        .logo-mark {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            box-shadow: 0 0 24px rgba(99,102,241,.4);
        }

        @keyframes fade-up {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fade-up .4s ease both; }
        .fade-up-2 { animation: fade-up .4s .08s ease both; }
        .fade-up-3 { animation: fade-up .4s .16s ease both; }
    </style>
</head>
<body class="relative flex items-center justify-center min-h-screen p-4">

<div class="relative z-10 w-full max-w-[420px] fade-up">

    {{-- Card --}}
    <div class="glass-card rounded-2xl p-8 sm:p-10">

        {{-- Logo / Brand --}}
        <div class="flex flex-col items-center mb-8 fade-up-2">
            <div class="logo-mark w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 3v1m0 16v1M4.22 4.22l.707.707M18.364 18.364l.707.707M1 12h1m20 0h1M4.22 19.778l.707-.707M18.364 5.636l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
            </div>
            <h1 class="text-white font-bold text-xl tracking-tight">{{ $tenant->name }}</h1>
            <p class="text-slate-400 text-sm mt-1">Sign in to your admin panel</p>
        </div>

        {{-- Error alert --}}
        @if($errors->any())
        <div class="mb-6 flex items-start gap-2.5 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm fade-up-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ $errors->first() }}
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.login') }}" class="space-y-5 fade-up-3">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Email</label>
                <div class="field-wrap">
                    <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="you@example.com"
                           class="w-full bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500">
                </div>
            </div>

            {{-- Password --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-widest">Password</label>
                    <a href="mailto:hello@faithstack.com?subject=Password+reset+request&body=Tenant:+{{ $tenant->subdomain }}"
                       class="text-xs text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
                        Forgot password?
                    </a>
                </div>
                <div class="field-wrap">
                    <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </span>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500">
                </div>
            </div>

            {{-- Remember me --}}
            <div class="flex items-center gap-2.5">
                <input type="checkbox" name="remember" id="remember"
                       class="w-4 h-4 rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-500/30 cursor-pointer">
                <label for="remember" class="text-sm text-slate-400 cursor-pointer select-none">Keep me signed in</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-signin w-full py-3.5 px-6 rounded-xl text-white font-bold text-sm tracking-wide mt-2">
                Sign In
            </button>
        </form>

        {{-- Back to website --}}
        <div class="mt-8 pt-6 border-t border-white/[0.06] text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-300 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to website
            </a>
        </div>
    </div>

    {{-- Powered by --}}
    <p class="mt-6 text-center text-xs text-slate-600">
        Powered by <span class="text-slate-500 font-semibold">FaithStack</span>
    </p>
</div>

</body>
</html>
