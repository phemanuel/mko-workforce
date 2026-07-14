<!-- Payroll History -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <!-- Header -->
    <div class="px-6 py-5 border-b border-slate-200">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-lg font-semibold text-slate-900">
                    Payroll History
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    View your payroll records and download payslips.
                </p>

            </div>

            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-sm font-medium">
                {{ $payrolls->total() }} Record{{ $payrolls->total() != 1 ? 's' : '' }}
            </span>

        </div>

    </div>

    @if($payrolls->count())

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Payroll
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Period
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Shifts
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Hours
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Gross
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Net
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Payment
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Actions
                    </th>

                </tr>

                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">

                @foreach($payrolls as $payroll)

                    <tr class="hover:bg-slate-50 transition">

                        <!-- Payroll Number -->
                        <td class="px-6 py-5">

                            <div class="font-semibold text-slate-900">
                                {{ $payroll->payroll_number }}
                            </div>

                            <div class="text-xs text-slate-500 mt-1">
                                Generated {{ $payroll->created_at->format('d M Y') }}
                            </div>

                        </td>

                        <!-- Period -->
                        <td class="px-6 py-5">

                            <div class="font-medium text-slate-800">
                                {{ $payroll->period_start->format('d M') }}
                                -
                                {{ $payroll->period_end->format('d M Y') }}
                            </div>

                        </td>

                        <!-- Shifts -->
                        <td class="px-6 py-5 text-center">

                            {{ $payroll->total_shifts }}

                        </td>

                        <!-- Hours -->
                        <td class="px-6 py-5 text-center">

                            {{ number_format($payroll->total_hours,2) }}

                        </td>

                        <!-- Gross -->
                        <td class="px-6 py-5 text-right font-semibold">

                            ₦{{ number_format($payroll->gross_pay,2) }}

                        </td>

                        <!-- Net -->
                        <td class="px-6 py-5 text-right">

                            <span class="font-bold text-green-600">

                                ₦{{ number_format($payroll->net_pay,2) }}

                            </span>

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

                        <!-- Payment Date -->
                        <td class="px-6 py-5 text-center text-sm text-slate-600">

                            {{ $payroll->payment_date ? $payroll->payment_date->format('d M Y') : '-' }}

                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-5">

                            <div class="flex justify-end gap-2">

                                <button
                                    class="viewPayroll inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition"
                                    data-url="{{ route('staff.payroll.show',$payroll) }}">

                                    View

                                </button>

                                @if($payroll->status === \App\Models\Payroll::STATUS_PAID)

                                    <a href="{{ route('staff.payroll.download',$payroll) }}"
                                       class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-100 transition">

                                        PDF

                                    </a>

                                @endif

                            </div>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

        <div class="px-6 py-5 border-t border-slate-200">

            {{ $payrolls->links() }}

        </div>

    @else

        <!-- Empty State -->
        <div class="py-20 px-6 text-center">

            <div class="w-20 h-20 mx-auto rounded-full bg-slate-100 flex items-center justify-center">

                <svg class="w-10 h-10 text-slate-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 14l2-2 4 4m6-10H3m18 0v14H3V6"/>

                </svg>

            </div>

            <h3 class="mt-6 text-lg font-semibold text-slate-900">
                No Payroll Records
            </h3>

            <p class="mt-2 text-slate-500">
                Your payroll history will appear here once payroll has been generated.
            </p>

        </div>

    @endif

</div>