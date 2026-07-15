<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    <!-- Eligible Employees -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">

        <div class="flex justify-between items-start">

            <div>

                <p class="text-sm text-slate-500">
                    Eligible Employees
                </p>

                <h2 class="mt-3 text-3xl font-bold text-slate-900">
                    {{ number_format($eligibleEmployees) }}
                </h2>

                <p class="mt-2 text-xs text-slate-400">
                    Ready for payroll generation
                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">

                👥

            </div>

        </div>

    </div>

    <!-- Pending Earnings -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">

        <div class="flex justify-between">

            <div>

                <p class="text-sm text-slate-500">
                    Pending Earnings
                </p>

                <h2 class="mt-3 text-3xl font-bold text-amber-600">

                    <!-- ₦,£,€,$ -->
                    ${{ number_format($pendingEarnings,2) }}

                </h2>

                <p class="mt-2 text-xs text-slate-400">

                    Awaiting payroll

                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center">

                💰

            </div>

        </div>

    </div>

    <!-- Draft Payrolls -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">

        <div class="flex justify-between">

            <div>

                <p class="text-sm text-slate-500">
                    Draft Payrolls
                </p>

                <h2 class="mt-3 text-3xl font-bold">

                    {{ $draftPayrolls }}

                </h2>

                <p class="mt-2 text-xs text-slate-400">

                    Awaiting approval

                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">

                📄

            </div>

        </div>

    </div>

    <!-- Paid This Month -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">

        <div class="flex justify-between">

            <div>

                <p class="text-sm text-slate-500">

                    Paid This Month

                </p>

                <h2 class="mt-3 text-3xl font-bold text-green-600">

                    ₦{{ number_format($paidThisMonth,2) }}

                </h2>

                <p class="mt-2 text-xs text-slate-400">

                    Successfully paid

                </p>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">

                ✅

            </div>

        </div>

    </div>

</div>