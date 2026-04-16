<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Login — FaithStack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-950 flex items-center justify-center px-4">

<div class="w-full max-w-sm">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-emerald-500 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/30">
            <span class="text-white font-black text-xl">FS</span>
        </div>
        <h1 class="text-2xl font-bold text-white">Platform Admin</h1>
        <p class="text-gray-400 text-sm mt-1">FaithStack Control Center</p>
    </div>

    {{-- Card --}}
    <div class="bg-gray-900 rounded-2xl border border-white/10 p-7 shadow-2xl">

        @if($errors->any())
            <div class="mb-5 px-4 py-3 rounded-xl bg-red-900/40 border border-red-700/50 text-red-300 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('superadmin.login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full bg-gray-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all"
                       placeholder="superadmin@faithstack.com">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Password</label>
                <input type="password" name="password" required
                       class="w-full bg-gray-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all"
                       placeholder="••••••••">
            </div>

            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-gray-800 border-white/20 accent-emerald-500">
                <span class="text-sm text-gray-400">Stay signed in</span>
            </label>

            <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3.5 rounded-xl text-sm transition-colors shadow-lg shadow-emerald-900/40">
                Access Platform →
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-gray-600 mt-6">
        Restricted access — authorized personnel only
    </p>
</div>

</body>
</html>
