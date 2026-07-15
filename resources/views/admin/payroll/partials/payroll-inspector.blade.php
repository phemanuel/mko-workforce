<!-- Payroll Inspector -->
<div id="payrollInspector"
     class="fixed inset-0 z-50 hidden">

    <!-- Overlay -->
    <div id="payrollInspectorOverlay"
         class="absolute inset-0 bg-black/40 backdrop-blur-sm">
    </div>

    <!-- Slide Over -->
    <div id="payrollInspectorPanel"
         class="absolute top-0 right-0
                h-full
                w-full lg:w-[850px]
                bg-white
                shadow-2xl
                transform translate-x-full
                transition-transform duration-300
                flex flex-col">

        <!-- ==========================================
             HEADER
        =========================================== -->

        <div class="flex items-center justify-between
                    px-6 py-5
                    border-b border-slate-200">

            <div>

                <h2 class="text-xl font-bold text-slate-900">
                    Payroll Inspector
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Review payroll details before approval or payment.
                </p>

            </div>

            <button
                type="button"
                id="closePayrollInspector"
                class="w-10 h-10
                       rounded-xl
                       hover:bg-slate-100
                       transition">

                ✕

            </button>

        </div>

        <!-- ==========================================
             SCROLLABLE CONTENT
        =========================================== -->

        <div class="flex-1 overflow-y-auto">

            <div class="p-6 space-y-6">

                <!-- ==========================================================
                    PAYROLL SUMMARY
                =========================================================== -->

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6">

                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h3 class="text-lg font-semibold text-slate-900">
                                Payroll Summary
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                Overview of the generated payroll.
                            </p>

                        </div>

                        <div id="summaryStatus">
                            <!-- Status badge inserted by JavaScript -->
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Payroll Number -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Payroll Number
                            </p>

                            <p id="summaryPayrollNumber"
                            class="mt-2 text-lg font-semibold text-slate-900">
                                —
                            </p>

                        </div>

                        <!-- Payroll Period -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Payroll Period
                            </p>

                            <p id="summaryPeriod"
                            class="mt-2 text-lg font-semibold text-slate-900">
                                —
                            </p>

                        </div>

                        <!-- Payment Date -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Payment Date
                            </p>

                            <p id="summaryPaymentDate"
                            class="mt-2 font-medium text-slate-700">
                                —
                            </p>

                        </div>

                        <!-- Payment Reference -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Payment Reference
                            </p>

                            <p id="summaryPaymentReference"
                            class="mt-2 font-medium text-slate-700 break-all">
                                —
                            </p>

                        </div>

                    </div>

                </div>

                <!-- ==========================================================
                    EMPLOYEE INFORMATION
                =========================================================== -->

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">

                    <!-- Card Header -->
                    <div class="mb-6">

                        <h3 class="text-lg font-semibold text-slate-900">
                            Employee Information
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Details of the employee this payroll belongs to.
                        </p>

                    </div>

                    <!-- Employee Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Employee Name -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Employee Name
                            </p>

                            <p id="employeeName"
                            class="mt-2 text-base font-semibold text-slate-900">
                                —
                            </p>

                        </div>

                        <!-- Employee Number -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Employee Number
                            </p>

                            <p id="employeeNumber"
                            class="mt-2 text-base font-medium text-slate-900">
                                —
                            </p>

                        </div>

                        <!-- Role -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Role
                            </p>

                            <p id="employeeRole"
                            class="mt-2 text-base font-medium text-slate-900">
                                —
                            </p>

                        </div>

                        <!-- Supervisor -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Supervisor
                            </p>

                            <p id="employeeSupervisor"
                            class="mt-2 text-base font-medium text-slate-900">
                                —
                            </p>

                        </div>

                    </div>

                </div>

                <!-- ==========================================================
                    EARNINGS SUMMARY
                =========================================================== -->

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">

                    <!-- Card Header -->
                    <div class="mb-6">

                        <h3 class="text-lg font-semibold text-slate-900">
                            Earnings Summary
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Summary of hours worked and earnings for this payroll period.
                        </p>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

                        <!-- Total Shifts -->
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">

                            <p class="text-sm text-slate-500">
                                Total Shifts
                            </p>

                            <h3 id="totalShifts"
                                class="mt-3 text-3xl font-bold text-slate-900">

                                —

                            </h3>

                        </div>

                        <!-- Total Hours -->
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">

                            <p class="text-sm text-slate-500">
                                Total Hours
                            </p>

                            <h3 id="totalHours"
                                class="mt-3 text-3xl font-bold text-slate-900">

                                —

                            </h3>

                        </div>

                        <!-- Gross Pay -->
                        <div class="rounded-xl border border-green-200 bg-green-50 p-5">

                            <p class="text-sm text-green-700">
                                Gross Pay
                            </p>

                            <h3 id="grossPay"
                                class="mt-3 text-3xl font-bold text-green-700">

                                ₦0.00

                            </h3>

                        </div>

                        <!-- Net Pay -->
                        <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-5">

                            <p class="text-sm text-indigo-700">
                                Net Pay
                            </p>

                            <h3 id="netPay"
                                class="mt-3 text-3xl font-bold text-indigo-700">

                                ₦0.00

                            </h3>

                        </div>

                    </div>

                </div>

                <!-- ==========================================================
                    PAYROLL BREAKDOWN
                =========================================================== -->

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">

                    <!-- Card Header -->
                    <div class="mb-6">

                        <h3 class="text-lg font-semibold text-slate-900">
                            Payroll Breakdown
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Detailed calculation showing earnings, additions and deductions.
                        </p>

                    </div>

                    <div class="overflow-hidden rounded-xl border border-slate-200">

                        <table class="min-w-full">

                            <tbody class="divide-y divide-slate-200">

                                <!-- Gross Pay -->
                                <tr>

                                    <td class="px-5 py-4 font-medium text-slate-700">
                                        Gross Pay
                                    </td>

                                    <td id="breakdownGrossPay"
                                        class="px-5 py-4 text-right font-semibold">

                                        ₦0.00

                                    </td>

                                </tr>

                                <!-- Allowance -->
                                <tr>

                                    <td class="px-5 py-4 text-slate-700">
                                        Allowance
                                    </td>

                                    <td id="allowance"
                                        class="px-5 py-4 text-right text-green-600">

                                        ₦0.00

                                    </td>

                                </tr>

                                <!-- Bonus -->
                                <tr>

                                    <td class="px-5 py-4 text-slate-700">
                                        Bonus
                                    </td>

                                    <td id="bonus"
                                        class="px-5 py-4 text-right text-green-600">

                                        ₦0.00

                                    </td>

                                </tr>

                                <!-- Tax -->
                                <tr>

                                    <td class="px-5 py-4 text-slate-700">
                                        Tax
                                    </td>

                                    <td id="tax"
                                        class="px-5 py-4 text-right text-red-600">

                                        ₦0.00

                                    </td>

                                </tr>

                                <!-- Other Deductions -->
                                <tr>

                                    <td class="px-5 py-4 text-slate-700">
                                        Other Deductions
                                    </td>

                                    <td id="deduction"
                                        class="px-5 py-4 text-right text-red-600">

                                        ₦0.00

                                    </td>

                                </tr>

                                <!-- Divider -->
                                <tr class="bg-slate-50">

                                    <td class="px-5 py-5 font-bold text-slate-900">

                                        Net Pay

                                    </td>

                                    <td id="breakdownNetPay"
                                        class="px-5 py-5 text-right text-xl font-bold text-indigo-700">

                                        ₦0.00

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <!-- ==========================================================
                    SHIFT BREAKDOWN
                =========================================================== -->

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">

                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h3 class="text-lg font-semibold text-slate-900">
                                Shift Breakdown
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Every completed shift included in this payroll.
                            </p>

                        </div>

                        <div>

                            <span id="shiftCountBadge"
                                class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">

                                0 Shifts

                            </span>

                        </div>

                    </div>

                    <!-- Table -->

                    <div class="overflow-x-auto rounded-xl border border-slate-200">

                        <table class="min-w-full divide-y divide-slate-200">

                            <thead class="bg-slate-50">

                                <tr>

                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Shift
                                    </th>

                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Date
                                    </th>

                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Hours
                                    </th>

                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Hourly Rate
                                    </th>

                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Amount
                                    </th>

                                </tr>

                            </thead>

                            <tbody id="payrollItemsTable"
                                class="divide-y divide-slate-100">

                                <!-- JavaScript inserts rows here -->

                                <tr>

                                    <td colspan="5"
                                        class="px-6 py-12 text-center text-slate-400">

                                        No payroll items loaded.

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <!-- ==========================================================
                    AUDIT TRAIL
                =========================================================== -->

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">

                    <!-- Card Header -->
                    <div class="mb-6">

                        <h3 class="text-lg font-semibold text-slate-900">
                            Audit Trail
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Record of payroll generation, approval and payment activities.
                        </p>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Generated By -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Generated By
                            </p>

                            <p id="generatedBy"
                            class="mt-2 font-semibold text-slate-900">

                                —

                            </p>

                        </div>

                        <!-- Generated On -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Generated On
                            </p>

                            <p id="generatedAt"
                            class="mt-2 text-slate-700">

                                —

                            </p>

                        </div>

                        <!-- Approved By -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Approved By
                            </p>

                            <p id="approvedBy"
                            class="mt-2 font-semibold text-slate-900">

                                —

                            </p>

                        </div>

                        <!-- Approved On -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Approved On
                            </p>

                            <p id="approvedAt"
                            class="mt-2 text-slate-700">

                                —

                            </p>

                        </div>

                        <!-- Paid By -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Paid By
                            </p>

                            <p id="paidBy"
                            class="mt-2 font-semibold text-slate-900">

                                —

                            </p>

                        </div>

                        <!-- Paid On -->
                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-500">
                                Paid On
                            </p>

                            <p id="paidAt"
                            class="mt-2 text-slate-700">

                                —

                            </p>

                        </div>

                    </div>

                    <!-- Remarks -->
                    <div class="mt-8 pt-6 border-t border-slate-200">

                        <p class="text-xs uppercase tracking-wide text-slate-500">
                            Remarks
                        </p>

                        <div id="remarks"
                            class="mt-3 rounded-xl bg-slate-50 border border-slate-200 p-4 text-slate-700">

                            No remarks available.

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ==========================================
             FOOTER
        =========================================== -->

        <div class="border-t border-slate-200
                    p-5">

            <div class="flex justify-end gap-3">

                <button
                    type="button"
                    id="approvePayrollBtn"
                    class="hidden
                           px-5 py-3
                           rounded-xl
                           bg-blue-600
                           text-white
                           hover:bg-blue-700
                           transition">

                    Approve Payroll

                </button>

                <button
                    type="button"
                    id="markPaidBtn"
                    class="hidden
                           px-5 py-3
                           rounded-xl
                           bg-green-600
                           text-white
                           hover:bg-green-700
                           transition">

                    Mark as Paid

                </button>

                <button
                    type="button"
                    id="closePayrollFooter"
                    class="px-5 py-3
                           rounded-xl
                           border border-slate-300
                           hover:bg-slate-100
                           transition">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>