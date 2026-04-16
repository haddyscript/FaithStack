<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        :root {
            --primary:   {{ $themeConfig['primary_color']   ?? '#0f172a' }};
            --secondary: {{ $themeConfig['secondary_color'] ?? '#6366f1' }};
        }
        .btn-accent { background-color: var(--secondary); color: #fff; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

<header class="border-b border-gray-100 bg-white px-6 py-4">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('home') }}" class="font-bold text-lg" style="color: var(--primary)">{{ $tenant->name }}</a>
    </div>
</header>

<main class="max-w-lg mx-auto px-6 py-16">
    <h1 class="text-4xl font-bold text-center mb-2 tracking-tight" style="color: var(--primary)">
        Support {{ $tenant->name }}
    </h1>
    <p class="text-center text-gray-400 mb-10">Every contribution helps us make a difference.</p>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('donate.store') }}" class="bg-white rounded-2xl shadow-sm border p-7 space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount ($)</label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">$</span>
                <input type="number" name="amount" value="{{ old('amount') }}" required min="1" step="0.01"
                       class="w-full border border-gray-200 rounded-lg pl-7 pr-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Frequency</label>
            <div class="flex gap-2">
                @foreach(['one_time' => 'One Time', 'monthly' => 'Monthly', 'yearly' => 'Yearly'] as $val => $label)
                    <label class="flex-1 text-center border rounded-lg py-2 text-sm cursor-pointer transition
                                  {{ old('frequency', 'one_time') === $val ? 'border-indigo-500 bg-indigo-50 font-semibold text-indigo-700' : 'border-gray-200 hover:bg-gray-50' }}">
                        <input type="radio" name="frequency" value="{{ $val }}"
                               {{ old('frequency', 'one_time') === $val ? 'checked' : '' }} class="sr-only">
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
            <textarea name="notes" rows="2"
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 outline-none">{{ old('notes') }}</textarea>
        </div>

        <button type="submit"
                class="w-full btn-accent font-semibold py-3 rounded-xl text-sm transition hover:opacity-90">
            Submit Donation
        </button>
    </form>
</main>

</body>
</html>
