@extends('layouts.admin')

@section('title','Payroll')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="max-w-7xl mx-auto px-6 py-8">

        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">

            <div>

                <h1 class="text-3xl font-bold text-slate-900">
                    Payroll
                </h1>

                <p class="mt-2 text-slate-500">

                    Manage payroll generation, approvals and payments.

                </p>

            </div>

            <div class="mt-5 lg:mt-0">

                <button
                    id="generatePayrollBtn"
                    class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition">

                   Preview and Generate Payroll

                </button>

            </div>

        </div>

        @include('admin.payroll.partials.stats')

        @include('admin.payroll.partials.quick-actions')

        @include('admin.payroll.partials.recent-payrolls')

    </div>

</div>

@include('admin.payroll.partials.generate-payroll-modal')

@include('admin.payroll.partials.payroll-inspector')


<script>
/*==========================================================
| Payroll Inspector Elements
==========================================================*/

const payrollInspector = document.getElementById('payrollInspector');
const payrollInspectorPanel = document.getElementById('payrollInspectorPanel');

const payrollInspectorOverlay = document.getElementById('payrollInspectorOverlay');

const closePayrollInspector = document.getElementById('closePayrollInspector');
const closePayrollFooter = document.getElementById('closePayrollFooter');

const approvePayrollBtn = document.getElementById('approvePayrollBtn');
const markPaidBtn = document.getElementById('markPaidBtn');

/*==========================================================
| Helper Functions
==========================================================*/

function formatMoney(amount)
{
    return '₦' + Number(amount ?? 0).toLocaleString('en-NG', {

        minimumFractionDigits: 2,

        maximumFractionDigits: 2

    });
}

function empty(value)
{
    return value ?? '—';
}

function formatDate(value)
{
    return value ? new Date(value).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) : '—';
}


/*==========================================================
| Open Payroll Inspector
==========================================================*/

function openPayrollInspector()
{
    payrollInspector.classList.remove('hidden');

    requestAnimationFrame(() => {

        payrollInspectorPanel.classList.remove('translate-x-full');

    });
}

/*==========================================================
| Close Payroll Inspector
==========================================================*/

function closePayrollInspectorPanel()
{
    payrollInspectorPanel.classList.add('translate-x-full');

    setTimeout(() => {

        payrollInspector.classList.add('hidden');

    }, 300);
}

/*==========================================================
| Close Events
==========================================================*/

closePayrollInspector.addEventListener('click', closePayrollInspectorPanel);

closePayrollFooter.addEventListener('click', closePayrollInspectorPanel);

payrollInspectorOverlay.addEventListener('click', closePayrollInspectorPanel);

/*==========================================================
| View Payroll
==========================================================*/

document.addEventListener('click', async function (e) {

    const button = e.target.closest('.viewPayroll');

    if (!button) return;

    openPayrollInspector();

    showPayrollLoading();

    try {

        const response = await fetch(button.dataset.url, {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Unable to load payroll.');
        }

        const payroll = await response.json();

        populatePayroll(payroll);

    } catch (error) {

        console.error(error);

        alert('Failed to load payroll details.');

    }

});

/*==========================================================
| Loading State
==========================================================*/

function showPayrollLoading()
{
    document.getElementById('summaryPayrollNumber').textContent = 'Loading...';
    document.getElementById('summaryPeriod').textContent = 'Loading...';
    document.getElementById('summaryPaymentDate').textContent = 'Loading...';
    document.getElementById('summaryPaymentReference').textContent = 'Loading...';

    document.getElementById('summaryStatus').innerHTML = `
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs">
            Loading...
        </span>
    `;

    document.getElementById('employeeName').textContent = 'Loading...';
    document.getElementById('employeeNumber').textContent = 'Loading...';
    document.getElementById('employeeRole').textContent = 'Loading...';
    document.getElementById('employeeSupervisor').textContent = 'Loading...';

    document.getElementById('payrollItemsTable').innerHTML = `
        <tr>
            <td colspan="5"
                class="px-6 py-10 text-center text-slate-500">
                Loading payroll items...
            </td>
        </tr>
    `;
}

/*==========================================================
| Populate Payroll
==========================================================*/

function populatePayroll(data)
{
    populateSummary(data);

    populateEmployee(data);

    populateEarnings(data);

    populateBreakdown(data);

    populateShiftBreakdown(data);

    populateAudit(data);

    updateActionButtons(data);
}

/*==========================================================
| Populate Payroll Summary
==========================================================*/

function populateSummary(data)
{
    /*
    |--------------------------------------------------------------------------
    | Payroll Summary
    |--------------------------------------------------------------------------
    */

    document.getElementById('summaryPayrollNumber').textContent =
        empty(data.payroll_number);

    document.getElementById('summaryPeriod').textContent =
        `${empty(data.period_start)} - ${empty(data.period_end)}`;

    document.getElementById('summaryPaymentDate').textContent =
        empty(data.payment_date);

    document.getElementById('summaryPaymentReference').textContent =
        empty(data.payment_reference);

    /*
    |--------------------------------------------------------------------------
    | Status Badge
    |--------------------------------------------------------------------------
    */

    let badge = '';

    switch (data.status) {

        case 'Paid':

            badge = `
                <span class="inline-flex items-center
                             px-3 py-1
                             rounded-full
                             bg-green-100
                             text-green-700
                             text-xs
                             font-semibold">

                    Paid

                </span>
            `;

            break;

        case 'Approved':

            badge = `
                <span class="inline-flex items-center
                             px-3 py-1
                             rounded-full
                             bg-blue-100
                             text-blue-700
                             text-xs
                             font-semibold">

                    Approved

                </span>
            `;

            break;

        case 'Processing':

            badge = `
                <span class="inline-flex items-center
                             px-3 py-1
                             rounded-full
                             bg-purple-100
                             text-purple-700
                             text-xs
                             font-semibold">

                    Processing

                </span>
            `;

            break;

        case 'Cancelled':

            badge = `
                <span class="inline-flex items-center
                             px-3 py-1
                             rounded-full
                             bg-red-100
                             text-red-700
                             text-xs
                             font-semibold">

                    Cancelled

                </span>
            `;

            break;

        default:

            badge = `
                <span class="inline-flex items-center
                             px-3 py-1
                             rounded-full
                             bg-amber-100
                             text-amber-700
                             text-xs
                             font-semibold">

                    Draft

                </span>
            `;
    }

    document.getElementById('summaryStatus').innerHTML = badge;
}

/*==========================================================
| Populate Employee Information
==========================================================*/

function populateEmployee(data)
{
    document.getElementById('employeeName').textContent =
        empty(data.employee.name);

    document.getElementById('employeeNumber').textContent =
        empty(data.employee.employee_number);

    document.getElementById('employeeRole').textContent =
        empty(data.employee.role);

    document.getElementById('employeeSupervisor').textContent =
        empty(data.employee.supervisor);
}

/*==========================================================
| Populate Earnings Summary
==========================================================*/

function populateEarnings(data)
{
    document.getElementById('totalShifts').textContent =
        empty(data.total_shifts);

    document.getElementById('totalHours').textContent =
        empty(data.total_hours);

    document.getElementById('grossPay').textContent =
        formatMoney(data.gross_pay);

    document.getElementById('netPay').textContent =
        formatMoney(data.net_pay);
}

/*==========================================================
| Populate Payroll Breakdown
==========================================================*/

function populateBreakdown(data)
{
    document.getElementById('breakdownGrossPay').textContent =
        formatMoney(data.gross_pay);

    document.getElementById('allowance').textContent =
        formatMoney(data.allowance);

    document.getElementById('bonus').textContent =
        formatMoney(data.bonus);

    document.getElementById('tax').textContent =
        formatMoney(data.tax);

    document.getElementById('deduction').textContent =
        formatMoney(data.deduction);

    document.getElementById('breakdownNetPay').textContent =
        formatMoney(data.net_pay);
}

/*==========================================================
| Populate Shift Breakdown
==========================================================*/

function populateShiftBreakdown(data)
{
    const tbody = document.getElementById('payrollItemsTable');

    const badge = document.getElementById('shiftCountBadge');

    /*
    |--------------------------------------------------------------------------
    | Number of shifts
    |--------------------------------------------------------------------------
    */

    badge.textContent = `${data.items.length} Shift${data.items.length === 1 ? '' : 's'}`;

    /*
    |--------------------------------------------------------------------------
    | No Items
    |--------------------------------------------------------------------------
    */

    if (data.items.length === 0) {

        tbody.innerHTML = `
            <tr>

                <td colspan="5"
                    class="px-6 py-10 text-center text-slate-500">

                    No payroll items found.

                </td>

            </tr>
        `;

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Build Rows
    |--------------------------------------------------------------------------
    */

    let rows = '';

    data.items.forEach(item => {

        rows += `

            <tr class="hover:bg-slate-50 transition">

                <td class="px-4 py-4">

                    <div class="font-medium text-slate-900">

                        ${item.shift}

                    </div>

                </td>

                <td class="px-4 py-4 text-center text-slate-600">

                    ${item.date}

                </td>

                <td class="px-4 py-4 text-center font-medium">

                    ${item.hours}

                </td>

                <td class="px-4 py-4 text-right">

                    ${formatMoney(item.rate)}

                </td>

                <td class="px-4 py-4 text-right font-semibold text-green-700">

                    ${formatMoney(item.amount)}

                </td>

            </tr>

        `;

    });

    tbody.innerHTML = rows;
}

/*==========================================================
| Populate Audit Trail
==========================================================*/

function populateAudit(data)
{
    document.getElementById('generatedBy').textContent =
        empty(data.generated_by);

    document.getElementById('generatedAt').textContent =
        empty(data.generated_at);

    document.getElementById('approvedBy').textContent =
        empty(data.approved_by);

    document.getElementById('approvedAt').textContent =
        empty(data.approved_at);

    document.getElementById('paidBy').textContent =
        empty(data.paid_by);

    document.getElementById('paidAt').textContent =
        empty(data.paid_at);

    document.getElementById('remarks').textContent =
        empty(data.remarks);
}

/*==========================================================
| Update Action Buttons
==========================================================*/

function updateActionButtons(data)
{
    /*
    |--------------------------------------------------------------------------
    | Store Payroll ID
    |--------------------------------------------------------------------------
    */

    approvePayrollBtn.dataset.id = data.id;

    markPaidBtn.dataset.id = data.id;

    /*
    |--------------------------------------------------------------------------
    | Hide Everything First
    |--------------------------------------------------------------------------
    */

    approvePayrollBtn.classList.add('hidden');

    markPaidBtn.classList.add('hidden');

    /*
    |--------------------------------------------------------------------------
    | Show Correct Buttons
    |--------------------------------------------------------------------------
    */

    switch (data.status) {

        case 'Draft':

        case 'Processing':

            approvePayrollBtn.classList.remove('hidden');

            break;

        case 'Approved':

            markPaidBtn.classList.remove('hidden');

            break;

        case 'Paid':

        case 'Cancelled':

        default:

            // Nothing to show

            break;
    }
}
</script>

<script>
    /*==========================================================
| Generate Payroll Modal
==========================================================*/

const generatePayrollBtn = document.getElementById('generatePayrollBtn');

const generatePayrollModal = document.getElementById('generatePayrollModal');

const generatePayrollOverlay = document.getElementById('generatePayrollOverlay');

const closeGeneratePayrollModal = document.getElementById('closeGeneratePayrollModal');

const cancelGeneratePayroll = document.getElementById('cancelGeneratePayroll');

const previewPayrollBtn = document.getElementById('previewPayrollBtn');

const confirmGeneratePayroll = document.getElementById('confirmGeneratePayroll');

const payrollPreviewContainer = document.getElementById('payrollPreviewContainer');

const payrollStartDate = document.getElementById('payrollStartDate');

const payrollEndDate = document.getElementById('payrollEndDate');

/*==========================================================
| Open Modal
==========================================================*/

generatePayrollBtn.addEventListener('click', function () {

    generatePayrollModal.classList.remove('hidden');

    document.body.classList.add('overflow-hidden');

});

/*==========================================================
| Close Modal
==========================================================*/

function closePayrollModal()
{
    generatePayrollModal.classList.add('hidden');

    document.body.classList.remove('overflow-hidden');

    /*
    |--------------------------------------------------------------------------
    | Reset Preview
    |--------------------------------------------------------------------------
    */

    payrollPreviewContainer.classList.add('hidden');

    payrollPreviewContainer.innerHTML = '';

    confirmGeneratePayroll.classList.add('hidden');
}

closeGeneratePayrollModal.addEventListener('click', closePayrollModal);

cancelGeneratePayroll.addEventListener('click', closePayrollModal);

generatePayrollOverlay.addEventListener('click', closePayrollModal);

document.addEventListener('keydown', function (e) {

    if (
        e.key === 'Escape' &&
        !generatePayrollModal.classList.contains('hidden')
    ) {

        closePayrollModal();

    }

});

/*==========================================================
| Validate Dates
==========================================================*/

function validatePayrollDates()
{
    if (payrollStartDate.value === '') {

        alert('Please select a start date.');

        payrollStartDate.focus();

        return false;
    }

    if (payrollEndDate.value === '') {

        alert('Please select an end date.');

        payrollEndDate.focus();

        return false;
    }

    if (payrollStartDate.value > payrollEndDate.value) {

        alert('Start date cannot be after the end date.');

        return false;
    }

    return true;
}


/*==========================================================
| Preview Payroll
==========================================================*/

previewPayrollBtn.addEventListener('click', previewPayroll);

async function previewPayroll()
{
    if (!validatePayrollDates()) {
        return;
    }

    previewPayrollBtn.disabled = true;

    previewPayrollBtn.innerHTML = `
        <span class="inline-flex items-center gap-2">
            <svg class="animate-spin h-5 w-5"
                 xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24">

                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4">
                </circle>

                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z">
                </path>

            </svg>

            Calculating...

        </span>
    `;

    try {

        const response = await fetch("{{ route('admin.payroll.preview') }}", {

            method: "POST",

            headers: {

                "Content-Type": "application/json",

                "X-CSRF-TOKEN": "{{ csrf_token() }}"

            },

            body: JSON.stringify({

                start_date: payrollStartDate.value,

                end_date: payrollEndDate.value

            })

        });

        const data = await response.json();

        if (!response.ok) {

            alert(data.message ?? "Unable to preview payroll.");

            return;

        }

        renderPreview(data);

    } catch (error) {

        console.error(error);

        alert("Something went wrong.");

    } finally {

        previewPayrollBtn.disabled = false;

        previewPayrollBtn.innerHTML = "Preview Payroll";

    }
}

/*==========================================================
| Render Payroll Preview
==========================================================*/

function renderPreview(data)
{
    payrollPreviewContainer.classList.remove('hidden');

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    document.getElementById('previewEmployees').textContent =
        data.summary.employees;

    document.getElementById('previewShifts').textContent =
        data.summary.completed_shifts;

    document.getElementById('previewHours').textContent =
        Number(data.summary.hours).toFixed(2);

    document.getElementById('previewGross').textContent =
        '₦' + Number(data.summary.gross_pay).toLocaleString();

    document.getElementById('previewPeriod').textContent =
        data.summary.period_start + ' → ' + data.summary.period_end;

    document.getElementById('previewExistingPayrolls').textContent =
        data.summary.existing_payrolls;

    document.getElementById('previewNewPayrolls').textContent =
        data.summary.new_payrolls;

    /*
    |--------------------------------------------------------------------------
    | Employee Table
    |--------------------------------------------------------------------------
    */

    let html = '';

    data.employees.forEach(function(employee, index){

        let badge = employee.status === 'Existing'
            ? '<span class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700">Existing</span>'
            : '<span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">New</span>';

        html += `

            <!-- Employee Row -->

            <tr
                class="cursor-pointer hover:bg-slate-50 transition employee-row"
                data-target="employee-${index}">

                <td class="px-6 py-4">

                    <div class="font-semibold">

                        ${employee.name}

                    </div>

                    <div class="text-sm text-slate-500">

                        ${employee.employee_number}

                    </div>

                </td>

                <td class="text-center">

                    ${employee.total_shifts}

                </td>

                <td class="text-center">

                    ${Number(employee.total_hours).toFixed(2)}

                </td>

                <td class="text-right font-semibold text-green-700 pr-6">

                    ₦${Number(employee.gross_pay).toLocaleString()}

                </td>

                <td class="text-center">

                    ${badge}

                </td>

            </tr>

            <!-- Hidden Breakdown -->

            <tr
                id="employee-${index}"
                class="hidden bg-slate-50">

                <td colspan="5">

                    <div class="p-6">

                        <table class="min-w-full">

                            <thead>

                                <tr class="text-sm text-slate-500">

                                    <th class="text-left pb-3">

                                        Shift

                                    </th>

                                    <th class="text-left">

                                        Date

                                    </th>

                                    <th>

                                        Hours

                                    </th>

                                    <th>

                                        Rate

                                    </th>

                                    <th class="text-right">

                                        Amount

                                    </th>

                                </tr>

                            </thead>

                            <tbody>
        `;

        employee.items.forEach(function(item){

            html += `

                <tr class="border-t">

                    <td class="py-3">

                        ${item.shift_title}

                    </td>

                    <td>

                        ${item.shift_date}

                    </td>

                    <td class="text-center">

                        ${Number(item.worked_hours).toFixed(2)}

                    </td>

                    <td class="text-center">

                        ₦${Number(item.hourly_rate).toLocaleString()}

                    </td>

                    <td class="text-right font-semibold">

                        ₦${Number(item.amount).toLocaleString()}

                    </td>

                </tr>

            `;

        });

        html += `

                            </tbody>

                        </table>

                    </div>

                </td>

            </tr>

        `;

    });

    document.getElementById('previewEmployeesTable').innerHTML = html;

    /*
    |--------------------------------------------------------------------------
    | Enable Generate Button
    |--------------------------------------------------------------------------
    */

    confirmGeneratePayroll.classList.remove('hidden');

    bindEmployeePreviewRows();
}

/*==========================================================
| Expand Employee Breakdown
==========================================================*/

function bindEmployeePreviewRows()
{
    document.querySelectorAll('.employee-row').forEach(function(row){

        row.addEventListener('click', function(){

            const target = document.getElementById(
                this.dataset.target
            );

            target.classList.toggle('hidden');

        });

    });
}

</script>

@endsection





