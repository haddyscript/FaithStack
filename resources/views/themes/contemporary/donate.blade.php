<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        :root {
            --primary:   {{ $themeConfig['primary_color']   ?? '#7c3aed' }};
            --secondary: {{ $themeConfig['secondary_color'] ?? '#06b6d4' }};
            --dark:      {{ $themeConfig['dark_color']      ?? '#0f172a' }};
        }
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
        }
        .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

<header class="bg-white border-b border-gray-100 px-6 py-4">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('home') }}" class="text-xl font-extrabold gradient-text">{{ $tenant->name }}</a>
    </div>
</header>

<main class="max-w-lg mx-auto px-6 py-16">
    <div class="text-center mb-10">
        <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center text-2xl" style="background: linear-gradient(135deg, var(--primary), var(--secondary));">
            ❤
        </div>
        <h1 class="text-4xl font-extrabold mb-2 gradient-text">Give Today</h1>
        <p class="text-gray-400">Support the mission of {{ $tenant->name }}</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-2xl px-5 py-4 text-sm font-medium">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 text-sm">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('donate.store') }}" class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8 space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-400 outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-400 outline-none transition">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-400 outline-none transition">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Amount</label>
            <div class="relative">
                <span class="absolute left-4 top-3 text-gray-400 text-sm font-semibold">$</span>
                <input type="number" name="amount" value="{{ old('amount') }}" required min="1" step="0.01"
                       placeholder="0.00"
                       class="w-full border border-gray-200 rounded-xl pl-8 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-400 outline-none transition">
            </div>
            {{-- Quick amounts --}}
            <div class="flex gap-2 mt-2">
                @foreach([25, 50, 100, 250] as $amount)
                    <button type="button"
                        onclick="document.querySelector('[name=amount]').value = '{{ $amount }}'"
                        class="flex-1 py-1.5 rounded-lg text-xs font-semibold border border-gray-200 text-gray-500 hover:border-purple-300 hover:text-purple-600 transition">
                        ${{ $amount }}
                    </button>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Frequency</label>
            <div class="flex gap-2">
                @foreach(['one_time' => 'One Time', 'monthly' => 'Monthly', 'yearly' => 'Yearly'] as $val => $label)
                    <label class="flex-1 text-center border-2 rounded-xl py-2.5 text-sm cursor-pointer transition-all duration-150
                                  {{ old('frequency', 'one_time') === $val ? 'border-purple-500 bg-purple-50 font-bold text-purple-700' : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                        <input type="radio" name="frequency" value="{{ $val }}"
                               {{ old('frequency', 'one_time') === $val ? 'checked' : '' }} class="sr-only">
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Notes (optional)</label>
            <textarea name="notes" rows="2" placeholder="Designated for..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-400 outline-none resize-none transition">{{ old('notes') }}</textarea>
        </div>

        <button type="submit"
                class="w-full btn-gradient font-extrabold py-4 rounded-2xl text-sm shadow-lg shadow-purple-500/20 hover:opacity-90 hover:-translate-y-0.5 transition-all duration-150">
            Complete Donation →
        </button>
    </form>
</main>

</body>
</html>
