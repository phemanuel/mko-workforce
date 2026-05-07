@extends('layouts.admin')

@section('page-title', 'Applications')

@section('content')

<div class="bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">Staff Applications</h2>

    <table class="w-full text-sm">

    <thead>
        <tr class="text-left border-b bg-gray-50">
            <th class="p-3">Name</th>
            <th>Email</th>
            <th>Step</th>
            <th>Status</th>
            <th class="text-right p-3">Action</th>
        </tr>
    </thead>

    <tbody>

    @forelse($applications as $app)

        <tr class="border-b hover:bg-gray-50">

            <!-- NAME -->
            <td class="p-3 font-medium">
                {{ $app->name }}
            </td>

            <!-- EMAIL -->
            <td>
                {{ $app->email }}
            </td>

            <!-- STEP -->
            <td>
                <span class="text-xs px-2 py-1 bg-gray-100 rounded">
                    Step {{ $app->registration_step }}/7
                </span>
            </td>

            <!-- STATUS -->
            <td>
                <span class="px-2 py-1 text-xs rounded
                    {{ $app->status == 'active'
                        ? 'bg-green-100 text-green-600'
                        : 'bg-yellow-100 text-yellow-600' }}">

                    {{ ucfirst($app->status) }}
                </span>
            </td>

            <!-- ACTION -->
            <td class="text-right p-3">

                <a href="{{ route('admin.applications.show', $app->id) }}"
                   class="bg-black text-white text-xs px-3 py-2 rounded hover:bg-gray-800 transition">
                    Review
                </a>

            </td>

        </tr>

    @empty

        <tr>
            <td colspan="5" class="p-6 text-center text-gray-500">
                No applications found.
            </td>
        </tr>

    @endforelse

    </tbody>

</table>

</div>

@endsection