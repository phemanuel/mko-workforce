<!-- Payroll Inspector -->
<div id="payrollInspector"
     class="fixed inset-0 z-50 hidden">

    <!-- Backdrop -->
    <div id="closePayrollInspector"
         class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- Panel -->
    <div id="payrollPanel"
         class="absolute right-0 top-0 h-full w-full md:w-[700px] bg-white shadow-2xl translate-x-full transition-transform duration-300 flex flex-col">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">

            <div>

                <h2 class="text-xl font-bold text-slate-900">
                    Payroll Details
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Payroll summary and earnings breakdown.
                </p>

            </div>

            <button id="closePayrollButton"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center">

                ✕

            </button>

        </div>

        <!-- Scroll Area -->
        <div class="flex-1 overflow-y-auto">

            <div class="p-6 space-y-6">

                <!-- Payroll Summary -->
                <div class="bg-slate-50 rounded-2xl p-5">

                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">
                        Payroll Summary
                    </h3>

                    <div class="grid grid-cols-2 gap-5">

                        <div>

                            <p class="text-xs text-slate-500">Payroll Number</p>

                            <p id="inspectorPayrollNumber"
                               class="font-semibold text-slate-900 mt-1">
                                —
                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-slate-500">Status</p>

                            <div id="inspectorStatus"
                                 class="mt-2">
                            </div>

                        </div>

                        <div>

                            <p class="text-xs text-slate-500">Period</p>

                            <p id="inspectorPeriod"
                               class="font-semibold mt-1">
                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-slate-500">Payment Date</p>

                            <p id="inspectorPaymentDate"
                               class="font-semibold mt-1">
                            </p>

                        </div>

                    </div>

                </div>

                <!-- Earnings -->
                <div>

                    <h3 class="text-lg font-semibold text-slate-900 mb-4">
                        Earnings
                    </h3>

                    <div class="space-y-3">

                        <div class="flex justify-between">
                            <span>Gross Pay</span>
                            <span id="grossPay"></span>
                        </div>

                        <div class="flex justify-between">
                            <span>Allowance</span>
                            <span id="allowance"></span>
                        </div>

                        <div class="flex justify-between">
                            <span>Bonus</span>
                            <span id="bonus"></span>
                        </div>

                        <div class="flex justify-between">
                            <span>Overtime</span>
                            <span id="overtime"></span>
                        </div>

                    </div>

                </div>

                <!-- Deductions -->
                <div>

                    <h3 class="text-lg font-semibold text-slate-900 mb-4">
                        Deductions
                    </h3>

                    <div class="space-y-3">

                        <div class="flex justify-between">
                            <span>Tax</span>
                            <span id="tax"></span>
                        </div>

                        <div class="flex justify-between">
                            <span>Pension</span>
                            <span id="pension"></span>
                        </div>

                        <div class="flex justify-between">
                            <span>NHF</span>
                            <span id="nhf"></span>
                        </div>

                        <div class="flex justify-between">
                            <span>Loan</span>
                            <span id="loan"></span>
                        </div>

                        <div class="flex justify-between">
                            <span>Other</span>
                            <span id="otherDeduction"></span>
                        </div>

                    </div>

                </div>

                <!-- Net Pay -->
                <div class="rounded-2xl bg-green-50 border border-green-200 p-6">

                    <p class="text-sm text-green-700">
                        Net Pay
                    </p>

                    <h2 id="netPay"
                        class="mt-2 text-4xl font-bold text-green-700">
                    </h2>

                </div>

                <!-- Shift Breakdown -->
                <div>

                    <div class="flex items-center justify-between mb-4">

                        <h3 class="text-lg font-semibold text-slate-900">
                            Shift Breakdown
                        </h3>

                    </div>

                    <div id="payrollItems"
                         class="space-y-4">

                    </div>

                </div>

                <!-- Audit Trail -->
                <div class="bg-slate-50 rounded-2xl p-5">

                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 mb-4">
                        Audit Trail
                    </h3>

                    <div class="space-y-3 text-sm">

                        <div class="flex justify-between">

                            <span>Generated By</span>

                            <span id="generatedBy"></span>

                        </div>

                        <div class="flex justify-between">

                            <span>Approved By</span>

                            <span id="approvedBy"></span>

                        </div>

                        <div class="flex justify-between">

                            <span>Paid By</span>

                            <span id="paidBy"></span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Footer -->
        <div class="border-t border-slate-200 p-5">

            <a id="downloadPayslip"
               href="#"
               class="w-full flex items-center justify-center rounded-xl bg-indigo-600 text-white py-3 font-medium hover:bg-indigo-700 transition">

                Download Payslip

            </a>

        </div>

    </div>

</div>