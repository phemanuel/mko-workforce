@extends('layouts.admin')

@section('title','Payroll')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="max-w-7xl mx-auto px-6 py-8">

        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">

            <div>

                <h1 class="text-3xl font-bold text-slate-900">
                    Payroll
                </h1>

                <p class="mt-2 text-slate-500">

                    Manage payroll generation, approvals and payments.

                </p>

            </div>

            <div class="mt-5 lg:mt-0">

                <button
                    id="generatePayrollBtn"
                    class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition">

                   Preview and Generate Payroll

                </button>

            </div>

        </div>

        @include('admin.payroll.partials.stats')

        @include('admin.payroll.partials.quick-actions')

        @include('admin.payroll.partials.recent-payrolls')

    </div>

</div>

@include('admin.payroll.partials.inspector')

@include('admin.payroll.partials.generate-modal')

@endsection

@push('scripts')

<script>

</script>

@endpush