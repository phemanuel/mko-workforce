<!-- ATTENDANCE INSPECTOR -->
<div id="attendanceInspector"
     class="fixed inset-y-0 right-0 z-[9999] w-full max-w-xl bg-white shadow-2xl
            translate-x-full transition-transform duration-300 ease-in-out">

    <div class="flex flex-col h-full">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-5 border-b">

            <div>

                <h2 class="text-xl font-bold text-gray-900">
                    Attendance Details
                </h2>

                <p class="text-sm text-gray-500">
                    View and manage attendance
                </p>

            </div>

            <button onclick="closeAttendanceInspector()"
                    class="w-10 h-10 rounded-xl hover:bg-gray-100 text-xl">

                ✕

            </button>

        </div>

        <!-- CONTENT -->
        <div id="attendanceInspectorBody"
             class="flex-1 overflow-y-auto p-6 space-y-6">

            <!-- Employee -->
            <div class="flex items-center gap-4">

                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl">

                    👤

                </div>

                <div>

                    <h3 id="ai_employee_name"
                        class="text-xl font-bold text-gray-900">
                    </h3>

                    <p id="ai_role"
                       class="text-gray-500">
                    </p>

                </div>

            </div>


            <!-- STATUS -->
            <div class="rounded-2xl border p-5">

                <div class="flex items-center justify-between">

                    <span class="text-gray-500">

                        Attendance Status

                    </span>

                    <span id="ai_status"
                          class="px-3 py-1 rounded-full text-sm font-semibold">

                    </span>

                </div>

            </div>


            <!-- SHIFT DETAILS -->
            <div class="rounded-2xl border p-5">

                <h4 class="font-semibold mb-4">

                    Shift Information

                </h4>

                <div class="grid grid-cols-2 gap-5 text-sm">

                    <div>

                        <p class="text-gray-500">Shift</p>

                        <p id="ai_shift"></p>

                    </div>

                    <div>

                        <p class="text-gray-500">Date</p>

                        <p id="ai_date"></p>

                    </div>

                    <div>

                        <p class="text-gray-500">Time</p>

                        <p id="ai_time"></p>

                    </div>

                    <div>

                        <p class="text-gray-500">Supervisor</p>

                        <p id="ai_supervisor"></p>

                    </div>

                </div>

            </div>


            <!-- CHECK-IN -->
            <div class="rounded-2xl border p-5">

                <h4 class="font-semibold mb-4">

                    Attendance

                </h4>

                <div class="space-y-4">

                    <div class="flex justify-between">

                        <span class="text-gray-500">

                            Check In

                        </span>

                        <strong id="ai_checkin">

                            --

                        </strong>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-gray-500">

                            Check Out

                        </span>

                        <strong id="ai_checkout">

                            --

                        </strong>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-gray-500">

                            Hours Worked

                        </span>

                        <strong id="ai_hours">

                            --

                        </strong>

                    </div>

                </div>

            </div>


            <!-- GPS -->
            <div class="rounded-2xl border p-5">

                <h4 class="font-semibold mb-4">

                    GPS Information

                </h4>

                <div class="space-y-3">

                    <div class="flex justify-between">

                        <span class="text-gray-500">

                            Check-In

                        </span>

                        <span id="ai_checkin_location">

                            --

                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-gray-500">

                            Check-Out

                        </span>

                        <span id="ai_checkout_location">

                            --

                        </span>

                    </div>

                </div>

            </div>


            <!-- NOTES -->
            <div class="rounded-2xl border p-5">

                <h4 class="font-semibold mb-4">

                    Admin Notes

                </h4>

                <textarea id="ai_notes"
                          rows="4"
                          class="w-full border rounded-xl p-3"
                          placeholder="Write notes..."></textarea>

            </div>

        </div>


        <!-- FOOTER -->
        <div class="border-t p-5 bg-gray-50">

            <div class="grid grid-cols-2 gap-3">

                <button
                    class="bg-green-600 hover:bg-green-700 text-white rounded-xl py-3">

                    ✓ Mark Present

                </button>

                <button
                    class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl py-3">

                    ⏰ Mark Late

                </button>

                <button
                    class="bg-red-600 hover:bg-red-700 text-white rounded-xl py-3">

                    ✕ No Show

                </button>

                <button
                    class="bg-gray-800 hover:bg-black text-white rounded-xl py-3">

                    Reset

                </button>

            </div>

        </div>

    </div>

</div>