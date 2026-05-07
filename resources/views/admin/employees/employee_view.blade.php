@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto p-6">

    <div class="bg-white p-6 rounded-lg shadow">

        <h2 class="text-xl font-bold mb-4">
            Employee Details
        </h2>

        <p><strong>Name:</strong> {{ $employee->user->name }}</p>
        <p><strong>Email:</strong> {{ $employee->user->email }}</p>
        <p><strong>Status:</strong> {{ $employee->status }}</p>

        <div class="mt-6 flex gap-3">

            <form method="POST" action="/admin/employees/{{ $employee->id }}/approve">
                @csrf
                <button class="bg-green-600 text-white px-4 py-2 rounded">
                    Approve
                </button>
            </form>

            <form method="POST" action="/admin/employees/{{ $employee->id }}/reject">
                @csrf
                <button class="bg-red-600 text-white px-4 py-2 rounded">
                    Reject
                </button>
            </form>

        </div>

    </div>

</div>

@endsection