<!-- ==========================================================
     GENERATE PAYROLL MODAL
=========================================================== -->

<div id="generatePayrollModal"
     class="fixed inset-0 z-50 hidden">

    <!-- Overlay -->
    <div id="generatePayrollOverlay"
         class="absolute inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    <!-- Modal -->
    <div class="relative flex items-center justify-center min-h-screen p-4">

    <div
        class="bg-white rounded-3xl shadow-2xl w-full max-w-6xl
               max-h-[90vh] flex flex-col overflow-hidden">

            <!-- Header -->
            <div class="px-8 py-6 border-b border-slate-200 flex justify-between items-center">

                <div>

                    <h2 class="text-2xl font-bold text-slate-900">
                        Preview Payroll
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Review payroll before generating.
                    </p>

                </div>

                <button
                    id="closeGeneratePayrollModal"
                    class="text-slate-500 hover:text-slate-700 text-2xl">

                    &times;

                </button>

            </div>

            <!-- Body -->          
            <div class="flex-1 overflow-y-auto p-8 space-y-8">

                <!-- Payroll Period -->
                <div>

                    <h3 class="font-semibold text-slate-900 mb-5">

                        Payroll Period

                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-2">

                                Start Date

                            </label>

                            <input
                                type="date"
                                id="payrollStartDate"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500">

                        </div>

                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-2">

                                End Date

                            </label>

                            <input
                                type="date"
                                id="payrollEndDate"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500">

                        </div>

                    </div>

                </div>

                <!-- Preview -->
                <!-- ==========================================================
                    PAYROLL PREVIEW
                =========================================================== -->

                <div id="payrollPreviewContainer"
                    class="hidden space-y-6">

                    <!-- =======================================================
                        SUMMARY
                    ======================================================== -->

                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6">

                        <div class="flex items-center justify-between mb-6">

                            <div>

                                <h3 class="text-lg font-semibold text-slate-900">

                                    Payroll Summary

                                </h3>

                                <p class="text-sm text-slate-500">

                                    Review the payroll before generation.

                                </p>

                            </div>

                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

                            <!-- Employees -->

                            <div class="bg-white rounded-xl border p-5">

                                <p class="text-sm text-slate-500">

                                    Employees

                                </p>

                                <h2 id="previewEmployees"
                                    class="mt-2 text-2xl font-bold">

                                    0

                                </h2>

                            </div>

                            <!-- Shifts -->

                            <div class="bg-white rounded-xl border p-5">

                                <p class="text-sm text-slate-500">

                                    Shifts

                                </p>

                                <h2 id="previewShifts"
                                    class="mt-2 text-2xl font-bold">

                                    0

                                </h2>

                            </div>

                            <!-- Hours -->

                            <div class="bg-white rounded-xl border p-5">

                                <p class="text-sm text-slate-500">

                                    Hours

                                </p>

                                <h2 id="previewHours"
                                    class="mt-2 text-2xl font-bold">

                                    0

                                </h2>

                            </div>

                            <!-- Gross -->

                            <div class="bg-white rounded-xl border p-5">

                                <p class="text-sm text-slate-500">

                                    Gross Payroll

                                </p>

                                <h2 id="previewGross"
                                    class="mt-2 text-2xl font-bold text-green-600">

                                    ₦0.00

                                </h2>

                            </div>

                        </div>

                        <div class="mt-6 grid md:grid-cols-3 gap-4">

                            <div>

                                <span class="text-sm text-slate-500">

                                    Payroll Period

                                </span>

                                <div id="previewPeriod"
                                    class="font-semibold mt-1">

                                    -

                                </div>

                            </div>

                            <div>

                                <span class="text-sm text-slate-500">

                                    Existing Payrolls

                                </span>

                                <div id="previewExistingPayrolls"
                                    class="font-semibold mt-1 text-amber-600">

                                    0

                                </div>

                            </div>

                            <div>

                                <span class="text-sm text-slate-500">

                                    New Payrolls

                                </span>

                                <div id="previewNewPayrolls"
                                    class="font-semibold mt-1 text-indigo-600">

                                    0

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- =======================================================
                        EMPLOYEE PREVIEW
                    ======================================================== -->

                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">

                        <div class="px-6 py-4 border-b">

                            <h3 class="font-semibold text-slate-900">

                                Employees To Be Paid

                            </h3>

                            <p class="text-sm text-slate-500 mt-1">

                                Click any employee to view their shift breakdown.

                            </p>

                        </div>

                        <div class="overflow-x-auto">

                            <table class="min-w-full">

                                <thead class="bg-slate-50">

                                    <tr>

                                        <th class="px-6 py-4 text-left">

                                            Employee

                                        </th>

                                        <th class="px-6 py-4 text-center">

                                            Shifts

                                        </th>

                                        <th class="px-6 py-4 text-center">

                                            Hours

                                        </th>

                                        <th class="px-6 py-4 text-right">

                                            Gross

                                        </th>

                                        <th class="px-6 py-4 text-center">

                                            Status

                                        </th>

                                    </tr>

                                </thead>

                                <tbody id="previewEmployeesTable">

                                    <!-- JS -->

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Footer -->
            <div class="px-8 py-6 border-t border-slate-200 flex justify-between">

                <button
                    id="previewPayrollBtn"
                    class="px-6 py-3 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition">

                    Preview Payroll

                </button>

                <div class="flex gap-3">

                    <button
                        id="cancelGeneratePayroll"
                        class="px-6 py-3 rounded-xl border border-slate-300 hover:bg-slate-50">

                        Cancel

                    </button>

                    <button
                        id="confirmGeneratePayroll"
                        class="hidden px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white">

                        Generate Payroll

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>