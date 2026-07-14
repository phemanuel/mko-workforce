<!-- Payroll Statistics -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    <!-- Total Earned -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300">

        <div class="flex items-start justify-between">

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Total Earned
                </p>

                <h2 class="mt-3 text-3xl font-bold text-slate-900">
                    ₦{{ number_format($totalEarned, 2) }}
                </h2>

                <p class="mt-2 text-xs text-slate-400">
                    Total amount received
                </p>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">

                <svg class="w-7 h-7 text-green-600"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8c-2.2 0-4 1.3-4 3s1.8 3 4 3 4 1.3 4 3-1.8 3-4 3m0-12V4m0 16v-2"/>

                </svg>

            </div>

        </div>

    </div>

    <!-- Paid Payrolls -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Paid Payrolls
                </p>

                <h2 class="mt-3 text-3xl font-bold text-slate-900">
                    {{ $paidPayrolls }}
                </h2>

                <p class="mt-2 text-xs text-slate-400">
                    Payrolls completed
                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">

                <svg class="w-7 h-7 text-indigo-600"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>

                </svg>

            </div>

        </div>

    </div>

    <!-- Pending Amount -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Pending Payment
                </p>

                <h2 class="mt-3 text-3xl font-bold text-amber-600">
                    ₦{{ number_format($pendingAmount,2) }}
                </h2>

                <p class="mt-2 text-xs text-slate-400">
                    Awaiting payment
                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center">

                <svg class="w-7 h-7 text-amber-600"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>

                </svg>

            </div>

        </div>

    </div>

    <!-- Current Month -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    This Month
                </p>

                <h2 class="mt-3 text-3xl font-bold text-slate-900">
                    ₦{{ number_format(optional($thisMonth)->net_pay ?? 0,2) }}
                </h2>

                <p class="mt-2 text-xs text-slate-400">
                    {{ optional($thisMonth)->status ?? 'No Payroll' }}
                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">

                <svg class="w-7 h-7 text-blue-600"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M8 7V3m8 4V3m-9 9h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                </svg>

            </div>

        </div>

    </div>

</div>