@extends('layouts.applicant')

@section('page-title', 'Complete Application')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="bg-white p-6 rounded-lg shadow">

        <h2 class="text-2xl font-bold">Employee Application</h2>

        <p class="text-gray-500 text-sm">
            Complete your application to get started.
        </p>

    </div>

    <!-- PROGRESS -->
    <div class="bg-white p-6 rounded-lg shadow">

        <div class="flex justify-between text-sm mb-2">
            <span>Progress</span>
            <span>Step {{ auth()->user()->registration_step }} of 7</span>
        </div>

        <div class="w-full bg-gray-200 h-2 rounded-full">
            <div class="bg-red-600 h-2 rounded-full"
                 style="width: {{ auth()->user()->registration_step * 14 }}%"></div>
        </div>

    </div>

    <!-- STEP FORMS -->
    <div class="bg-white p-6 rounded-lg shadow">

        {{-- STEP 2 --}}
        @if(auth()->user()->registration_step == 2)

            <h3 class="font-semibold mb-4">Personal Details</h3>

            <form method="POST" action="{{ route('application.step1') }}">
                @csrf

                <input type="text" name="phone" placeholder="Phone"
                    class="w-full border p-2 mb-3">

                <input type="text" name="address" placeholder="Address"
                    class="w-full border p-2 mb-3">

                <input type="date" name="date_of_birth"
                    class="w-full border p-2 mb-3">

                <button class="bg-red-600 text-white px-4 py-2 rounded">
                    Save & Continue
                </button>
            </form>

        @endif


        {{-- STEP 3 --}}
        @if(auth()->user()->registration_step == 3)

            <h3 class="font-semibold mb-4">Upload Documents</h3>

            <form method="POST" enctype="multipart/form-data"
                  action="{{ route('application.step2') }}">
                @csrf

                <input type="file" name="passport" class="mb-3">
                <input type="file" name="dbs" class="mb-3">

                <button class="bg-red-600 text-white px-4 py-2 rounded">
                    Upload & Continue
                </button>
            </form>

        @endif

    </div>

</div>

@endsection