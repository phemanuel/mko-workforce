@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 p-6">

    <div class="max-w-6xl mx-auto">

        <!-- HEADER -->
        <div class="bg-black text-white p-6 rounded-xl mb-6">

            <h1 class="text-2xl font-bold">
                Welcome, {{ auth()->user()->name }}
            </h1>

            <p class="text-gray-300 mt-1">
                Role: {{ auth()->user()->role->name }}
            </p>

        </div>

        <!-- STATUS CARD -->
        <div class="bg-white p-6 rounded-xl shadow">

            <h2 class="text-lg font-semibold mb-2">
                Account Status
            </h2>

            <span class="px-4 py-2 rounded-full text-white
                {{ auth()->user()->status === 'approved' ? 'bg-green-600' : 'bg-red-600' }}">

                {{ strtoupper(auth()->user()->status) }}

            </span>

        </div>

    </div>

</div>

@endsection