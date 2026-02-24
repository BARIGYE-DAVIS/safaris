@extends('layouts.admin')

@section('title', 'Subscribers')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Subscribers</h1>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="py-2">Email</th>
                        <th class="py-2">Subscribed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $sub)
                        <tr class="border-t">
                            <td class="py-3">{{ $sub->email }}</td>
                            <td class="py-3">{{ $sub->created_at ? $sub->created_at->toDayDateTimeString() : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-6 text-center text-gray-500">No subscribers yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $subscribers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection