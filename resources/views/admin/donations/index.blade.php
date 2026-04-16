@extends('admin.layouts.app')

@section('title', 'Donations')
@section('heading', 'Donations')

@section('content')
<div class="bg-white rounded-xl border shadow-sm overflow-hidden">
    @if($donations->isEmpty())
        <div class="p-8 text-center text-gray-500 text-sm">No donations yet.</div>
    @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Donor</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Email</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Amount</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Frequency</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($donations as $donation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $donation->full_name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $donation->email }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-800">${{ number_format($donation->amount, 2) }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ str_replace('_', ' ', $donation->frequency) }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full
                                {{ $donation->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($donation->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-400">{{ $donation->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-3 border-t">
            {{ $donations->links() }}
        </div>
    @endif
</div>
@endsection
