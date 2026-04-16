<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-lg font-bold truncate">{{ $tenant->name }}</h1>
            <p class="text-xs text-gray-400 mt-1">FaithStack Admin</p>
        </div>
        <nav class="flex-1 p-4 space-y-1 text-sm">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
               Dashboard
            </a>
            <a href="{{ route('admin.pages.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.pages.*') ? 'bg-gray-700' : '' }}">
               Pages
            </a>
            <a href="{{ route('admin.navigation.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.navigation.*') ? 'bg-gray-700' : '' }}">
               Navigation
            </a>
            <a href="{{ route('admin.donations.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.donations.*') ? 'bg-gray-700' : '' }}">
               Donations
            </a>
            <a href="{{ route('admin.settings') }}"
               class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.settings') ? 'bg-gray-700' : '' }}">
               Settings
            </a>
        </nav>
        <div class="p-4 border-t border-gray-700">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="w-full text-left text-sm text-gray-400 hover:text-white px-3 py-2">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 overflow-y-auto">
        <header class="bg-white border-b px-6 py-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">@yield('heading', 'Dashboard')</h2>
            @yield('header-actions')
        </header>

        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-300 text-green-800 rounded px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-300 text-red-800 rounded px-4 py-3 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</div>

</body>
</html>
