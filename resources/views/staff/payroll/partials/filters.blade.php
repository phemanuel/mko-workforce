<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">

    <div class="flex flex-col lg:flex-row lg:items-end gap-5">

        <!-- Search -->
        <div class="flex-1">

            <label for="searchPayroll"
                   class="block text-sm font-medium text-slate-700 mb-2">
                Search Payroll
            </label>

            <div class="relative">

                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">

                    <svg class="w-5 h-5 text-slate-400"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>

                    </svg>

                </div>

                <input
                    type="text"
                    id="searchPayroll"
                    placeholder="Search payroll number or period..."
                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                           transition">

            </div>

        </div>

        <!-- Status -->
        <div class="w-full lg:w-52">

            <label for="statusFilter"
                   class="block text-sm font-medium text-slate-700 mb-2">
                Status
            </label>

            <select id="statusFilter"
                    class="w-full rounded-xl border border-slate-300 py-3 px-4
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                <option value="">All Statuses</option>

                <option value="Draft">Draft</option>

                <option value="Approved">Approved</option>

                <option value="Paid">Paid</option>

                <option value="Cancelled">Cancelled</option>

            </select>

        </div>

        <!-- Year -->
        <div class="w-full lg:w-44">

            <label for="yearFilter"
                   class="block text-sm font-medium text-slate-700 mb-2">
                Year
            </label>

            <select id="yearFilter"
                    class="w-full rounded-xl border border-slate-300 py-3 px-4
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                <option value="">All Years</option>

                @for($year = now()->year; $year >= now()->year - 5; $year--)
                    <option value="{{ $year }}">
                        {{ $year }}
                    </option>
                @endfor

            </select>

        </div>

        <!-- Clear -->
        <div>

            <button
                id="clearFilters"
                type="button"
                class="w-full lg:w-auto px-6 py-3 rounded-xl
                       border border-slate-300
                       hover:bg-slate-100
                       transition">

                Clear

            </button>

        </div>

    </div>

</div>