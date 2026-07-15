<!-- Recent Payrolls -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <!-- Header -->
    <div class="px-6 py-5 border-b border-slate-200">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <h2 class="text-lg font-semibold text-slate-900">
                    Recent Payrolls
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Review generated payrolls, approvals and payments.
                </p>

            </div>

            <div class="flex flex-col sm:flex-row gap-3">

                <!-- Search -->
                <div class="relative">

                    <input
                        type="text"
                        id="searchPayroll"
                        placeholder="Search..."
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                    <svg class="absolute left-3 top-3.5 w-4 h-4 text-slate-400"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M21 21l-4.3-4.3M10 18a8 8 0 100-16 8 8 0 000 16z"/>

                    </svg>

                </div>

                <!-- Status -->
                <select
                    id="statusFilter"
                    class="rounded-xl border border-slate-300 px-4 py-2.5">

                    <option value="">All Status</option>

                    <option>Draft</option>

                    <option>Approved</option>

                    <option>Paid</option>

                    <option>Cancelled</option>

                </select>

            </div>

        </div>

    </div>

    @if($recentPayrolls->count())

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left">
                        Payroll
                    </th>

                    <th class="px-6 py-4 text-left">
                        Employee
                    </th>

                    <th class="px-6 py-4 text-left">
                        Period
                    </th>

                    <th class="px-6 py-4 text-center">
                        Shifts
                    </th>

                    <th class="px-6 py-4 text-right">
                        Gross
                    </th>

                    <th class="px-6 py-4 text-right">
                        Net
                    </th>

                    <th class="px-6 py-4 text-center">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center">
                        Actions
                    </th>

                </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                @foreach($recentPayrolls as $payroll)

                    <tr class="hover:bg-slate-50 transition">

                        <!-- Payroll -->

                        <td class="px-6 py-5">

                            <div class="font-semibold text-slate-900">

                                {{ $payroll->payroll_number }}

                            </div>

                            <div class="text-xs text-slate-500 mt-1">

                                {{ $payroll->created_at->format('d M Y') }}

                            </div>

                        </td>

                        <!-- Employee -->

                        <td class="px-6 py-5">

                            <div class="font-medium">

                                {{ $payroll->employee->user->name }}

                            </div>

                            <div class="text-xs text-slate-500">

                                {{ $payroll->employee->employee_number }}

                            </div>

                        </td>

                        <!-- Period -->

                        <td class="px-6 py-5">

                            {{ $payroll->period_start->format('d M') }}

                            -

                            {{ $payroll->period_end->format('d M Y') }}

                        </td>

                        <!-- Shifts -->

                        <td class="px-6 py-5 text-center">

                            {{ $payroll->total_shifts }}

                        </td>

                        <!-- Gross -->

                        <td class="px-6 py-5 text-right font-medium">

                            ₦{{ number_format($payroll->gross_pay,2) }}

                        </td>

                        <!-- Net -->

                        <td class="px-6 py-5 text-right font-bold text-green-600">

                            ₦{{ number_format($payroll->net_pay,2) }}

                        </td>

                        <!-- Status -->

                        <td class="px-6 py-5 text-center">

                            @switch($payroll->status)

                                @case('Paid')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">

                                        Paid

                                    </span>

                                @break

                                @case('Approved')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">

                                        Approved

                                    </span>

                                @break

                                @case('Draft')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">

                                        Draft

                                    </span>

                                @break

                                @default

                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">

                                        Cancelled

                                    </span>

                            @endswitch

                        </td>

                        <!-- Actions -->

                        <td class="px-6 py-5">

                            <div class="flex justify-center gap-2">

                                <button
                                    type="button"
                                    class="viewPayroll px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition"
                                    data-url="{{ route('admin.payroll.show', $payroll) }}">

                                    View

                                </button>

                                @if($payroll->status == 'Draft')

                                    <button
                                        class="approvePayroll px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                                        data-id="{{ $payroll->id }}">

                                        Approve

                                    </button>

                                @endif

                                @if($payroll->status == 'Approved')

                                    <button
                                        class="markPaid px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition"
                                        data-id="{{ $payroll->id }}">

                                        Mark Paid

                                    </button>

                                @endif

                            </div>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="py-20 text-center">

            <div class="mx-auto w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center">

                💰

            </div>

            <h3 class="mt-6 text-lg font-semibold text-slate-900">

                No Payroll Generated

            </h3>

            <p class="mt-2 text-slate-500">

                Click "Generate Payroll" to create payroll for completed shifts.

            </p>

        </div>

    @endif

</div>