<!-- ==========================================================
     FLOATING QUICK ACTIONS
=========================================================== -->

<div class="fixed bottom-6 right-6 z-50">

    <!-- Action Menu -->
    <div id="quickActionsMenu"
         class="hidden mb-3 w-60 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">

        <button id="generatePayrollBtn"
            class="w-full text-left px-5 py-4 hover:bg-slate-50 transition border-b">

            Generate Payroll

        </button>

        <button
            class="w-full text-left px-5 py-4 hover:bg-slate-50 transition border-b">

            Payroll History

        </button>

        <button
            class="w-full text-left px-5 py-4 hover:bg-slate-50 transition border-b">

            Export Excel

        </button>

        <button
            class="w-full text-left px-5 py-4 hover:bg-slate-50 transition">

            Export PDF

        </button>

    </div>

    <!-- Floating Button -->
    <button
        id="toggleQuickActions"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-4 rounded-full shadow-xl transition flex items-center gap-2">

        ⚡

        <span>Quick Actions</span>

    </button>

</div>

<script>
    /*==========================================================
| Quick Actions
==========================================================*/

const quickActionsButton = document.getElementById('toggleQuickActions');

const quickActionsMenu = document.getElementById('quickActionsMenu');

quickActionsButton.addEventListener('click', function () {

    quickActionsMenu.classList.toggle('hidden');

});

/*
|--------------------------------------------------------------------------
| Close when clicking outside
|--------------------------------------------------------------------------
*/

document.addEventListener('click', function (e) {

    if (!e.target.closest('.fixed')) {

        quickActionsMenu.classList.add('hidden');

    }

});
</script>