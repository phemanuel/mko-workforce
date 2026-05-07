@extends('layouts.app')

@section('content')

<div class="p-6 max-w-7xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Employee Vetting</h1>

    <div class="bg-white shadow rounded-lg overflow-hidden">

        <table class="w-full text-left">

            <thead class="bg-black text-white">
                <tr>
                    <th class="p-4">Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach($employees as $employee)

                    <tr class="border-b">

                        <td class="p-4">
                            {{ $employee->user->name }}
                        </td>

                        <td>
                            {{ $employee->user->email }}
                        </td>

                        <td>
                            <span class="px-3 py-1 text-white rounded
                                {{ $employee->status === 'approved' ? 'bg-green-600' : ($employee->status === 'rejected' ? 'bg-red-600' : 'bg-yellow-500') }}">
                                {{ strtoupper($employee->status) }}
                            </span>
                        </td>

                        <td class="flex gap-2 p-4">

                            <a href="/admin/employees/{{ $employee->id }}"
                               class="bg-gray-800 text-white px-3 py-1 rounded">
                                View
                            </a>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection