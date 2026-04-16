<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Merriweather', serif; }
        :root {
            --primary:   {{ $themeConfig['primary_color']   ?? '#1e3a8a' }};
            --secondary: {{ $themeConfig['secondary_color'] ?? '#f59e0b' }};
        }
        .bg-primary { background-color: var(--primary); }
        .btn-primary { background-color: var(--primary); color: #fff; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<header class="bg-primary text-white px-6 py-4">
    <div class="max-w-4xl mx-auto flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-lg font-bold">{{ $tenant->name }}</a>
    </div>
</header>

<main class="max-w-lg mx-auto px-6 py-16">
    <h1 class="text-3xl font-bold text-center mb-2">Give to {{ $tenant->name }}</h1>
    <p class="text-center text-gray-500 mb-8 text-sm">Your generosity makes a difference.</p>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-300 text-green-800 rounded-lg px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-300 text-red-700 rounded-lg px-4 py-3 text-sm">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('donate.store') }}" class="bg-white rounded-2xl shadow p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount ($)</label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-400 text-sm">$</span>
                <input type="number" name="amount" value="{{ old('amount') }}" required min="1" step="0.01"
                       class="w-full border border-gray-300 rounded-lg pl-7 pr-3 py-2 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Frequency</label>
            <div class="flex gap-3">
                @foreach(['one_time' => 'One Time', 'monthly' => 'Monthly', 'yearly' => 'Yearly'] as $val => $label)
                    <label class="flex-1 text-center border rounded-lg py-2 text-sm cursor-pointer
                                  {{ old('frequency', 'one_time') === $val ? 'border-blue-500 bg-blue-50 font-semibold' : 'border-gray-300' }}">
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
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('notes') }}</textarea>
        </div>

        <button type="submit"
                class="w-full btn-primary font-semibold py-3 rounded-lg text-sm transition hover:opacity-90">
            Submit Donation
        </button>
    </form>
</main>

</body>
</html>
