<!-- ATTENDANCE INSPECTOR -->
<div id="attendanceInspector"
     class="fixed inset-y-0 right-0 z-[9999] w-full md:w-[620px] bg-white shadow-2xl
            translate-x-full transition-transform duration-300 ease-in-out flex flex-col">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white px-6 py-5">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-xl font-bold">
                    Attendance Details
                </h2>

                <p class="text-sm text-slate-300 mt-1">
                    Employee attendance information
                </p>

            </div>

            <button
                onclick="closeAttendanceInspector()"
                class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20">

                ✕

            </button>

        </div>

    </div>

    <!-- BODY -->
    <div class="flex-1 overflow-y-auto p-6 space-y-6">

        <!-- EMPLOYEE -->
        <div class="bg-slate-50 rounded-2xl p-5">

            <div class="flex items-center gap-4">

                <div class="w-16 h-16 rounded-full bg-slate-200 flex items-center justify-center text-2xl">

                    👤

                </div>

                <div>

                    <h3
                        id="inspectorEmployee"
                        class="text-xl font-bold">

                        --

                    </h3>

                    <p
                        id="inspectorRole"
                        class="text-gray-500">

                        --

                    </p>

                </div>

            </div>

        </div>


        <!-- SHIFT INFORMATION -->

        <div class="bg-white border rounded-2xl p-5">

            <h3 class="font-bold text-lg mb-4">

                Shift Information

            </h3>

            <div class="grid grid-cols-2 gap-4">

                <div>

                    <p class="text-gray-500 text-sm">

                        Shift

                    </p>

                    <p
                        id="inspectorShift">

                        --

                    </p>

                </div>

                <div>

                    <p class="text-gray-500 text-sm">

                        Date

                    </p>

                    <p
                        id="inspectorDate">

                        --

                    </p>

                </div>

                <div>

                    <p class="text-gray-500 text-sm">

                        Time

                    </p>

                    <p
                        id="inspectorTime">

                        --

                    </p>

                </div>

                <div>

                    <p class="text-gray-500 text-sm">

                        Status

                    </p>

                    <span
                        id="inspectorStatus"
                        class="inline-block px-3 py-1 rounded-full bg-gray-100">

                        --

                    </span>

                </div>

            </div>

        </div>


        <!-- ATTENDANCE -->

        <div class="bg-white border rounded-2xl p-5">

            <h3 class="font-bold text-lg mb-4">

                Attendance

            </h3>

            <div class="space-y-4">

                <div class="flex justify-between">

                    <span>

                        Check In

                    </span>

                    <strong
                        id="inspectorCheckIn">

                        --

                    </strong>

                </div>

                <div class="flex justify-between">

                    <span>

                        Check Out

                    </span>

                    <strong
                        id="inspectorCheckOut">

                        --

                    </strong>

                </div>

                <div class="flex justify-between">

                    <span>

                        Worked Hours

                    </span>

                    <strong
                        id="inspectorHours">

                        --

                    </strong>

                </div>

                <div class="flex justify-between">

                    <span>

                        Attendance Status

                    </span>

                    <strong
                        id="attendanceFlag">

                        --

                    </strong>

                </div>

            </div>

        </div>



        <!-- GPS -->

        <div class="bg-white border rounded-2xl p-5">

            <h3 class="font-bold text-lg mb-4">

                GPS Information

            </h3>

            <div class="space-y-3">

                <div>

                    <p class="text-sm text-gray-500">

                        Check In Location

                    </p>

                    <p
                        id="checkInLocation">

                        --

                    </p>

                </div>

                <div>

                    <p class="text-sm text-gray-500">

                        Check Out Location

                    </p>

                    <p
                        id="checkOutLocation">

                        --

                    </p>

                </div>

            </div>

        </div>



        <!-- SUPERVISOR -->

        <div class="bg-white border rounded-2xl p-5">

            <h3 class="font-bold text-lg mb-4">

                Supervisor

            </h3>

            <div
                id="supervisorName">

                --

            </div>

        </div>



        <!-- NOTES -->

        <div class="bg-white border rounded-2xl p-5">

            <h3 class="font-bold text-lg mb-4">

                Shift Notes

            </h3>

            <div
                id="shiftNotes"
                class="text-gray-600">

                No notes available.

            </div>

        </div>



        <!-- TIMELINE -->

        <div class="bg-white border rounded-2xl p-5">

            <h3 class="font-bold text-lg mb-5">

                Timeline

            </h3>

            <div
                id="attendanceTimeline"
                class="space-y-4">

            </div>

        </div>

    </div>


    <!-- FOOTER -->

    <div class="border-t p-5 bg-white">

        <button
            onclick="closeAttendanceInspector()"
            class="w-full bg-slate-900 hover:bg-slate-800 text-white rounded-xl py-3">

            Close Inspector

        </button>

    </div>

</div>



<!-- BACKDROP -->

<div
    id="attendanceInspectorBackdrop"
    onclick="closeAttendanceInspector()"
    class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[9998]">
</div>