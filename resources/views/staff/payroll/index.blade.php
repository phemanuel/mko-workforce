@extends('layouts.staff')

@section('title', 'Payroll')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-8">

            <h1 class="text-3xl font-bold text-slate-900">
                Payroll
            </h1>

            <p class="mt-2 text-slate-500">
                View your earnings, payroll history and download payslips.
            </p>

        </div>

        <!-- Statistics -->
        @include('staff.payroll.partials.stats')

        <!-- Filters -->
        @include('staff.payroll.partials.filters')

        <!-- Payroll Table -->
        @include('staff.payroll.partials.table')

    </div>

</div>

@include('staff.payroll.partials.inspector')

@endsection



<script>

const inspector = document.getElementById('payrollInspector');
const panel = document.getElementById('payrollPanel');

const closeBackdrop = document.getElementById('closePayrollInspector');
const closeButton = document.getElementById('closePayrollButton');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function formatCurrency(amount)
{
    return '₦' + Number(amount ?? 0).toLocaleString('en-NG', {
        minimumFractionDigits:2,
        maximumFractionDigits:2
    });
}

function formatDate(date)
{
    if(!date) return '-';

    return new Date(date).toLocaleDateString('en-GB',{
        day:'2-digit',
        month:'short',
        year:'numeric'
    });
}

/*
|--------------------------------------------------------------------------
| Open Inspector
|--------------------------------------------------------------------------
*/

async function openPayroll(url)
{
    inspector.classList.remove('hidden');

    requestAnimationFrame(() => {

        panel.classList.remove('translate-x-full');

    });

    try{

        const response = await fetch(url);

        const payroll = await response.json();

        populateInspector(payroll);

    }catch(error){

        console.error(error);

    }

}

/*
|--------------------------------------------------------------------------
| Close Inspector
|--------------------------------------------------------------------------
*/

function closePayroll()
{
    panel.classList.add('translate-x-full');

    setTimeout(()=>{

        inspector.classList.add('hidden');

    },300);
}

/*
|--------------------------------------------------------------------------
| Populate Inspector
|--------------------------------------------------------------------------
*/

function populateInspector(payroll)
{

    document.getElementById('inspectorPayrollNumber').textContent =
        payroll.payroll_number;

    document.getElementById('inspectorPeriod').textContent =
        formatDate(payroll.period_start) +
        ' - ' +
        formatDate(payroll.period_end);

    document.getElementById('inspectorPaymentDate').textContent =
        payroll.payment_date
            ? formatDate(payroll.payment_date)
            : 'Pending';

    /*
    |--------------------------------------------------------------------------
    | Status Badge
    |--------------------------------------------------------------------------
    */

    let badge = '';

    switch(payroll.status){

        case 'Paid':

            badge =
            `<span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                Paid
            </span>`;

        break;

        case 'Approved':

            badge =
            `<span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-medium">
                Approved
            </span>`;

        break;

        case 'Draft':

            badge =
            `<span class="inline-flex px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-sm font-medium">
                Draft
            </span>`;

        break;

        default:

            badge =
            `<span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">
                Cancelled
            </span>`;

    }

    document.getElementById('inspectorStatus').innerHTML = badge;

    /*
    |--------------------------------------------------------------------------
    | Earnings
    |--------------------------------------------------------------------------
    */

    document.getElementById('grossPay').textContent =
        formatCurrency(payroll.gross_pay);

    document.getElementById('allowance').textContent =
        formatCurrency(payroll.allowance);

    document.getElementById('bonus').textContent =
        formatCurrency(payroll.bonus);

    document.getElementById('overtime').textContent =
        formatCurrency(payroll.overtime);

    /*
    |--------------------------------------------------------------------------
    | Deductions
    |--------------------------------------------------------------------------
    */

    document.getElementById('tax').textContent =
        formatCurrency(payroll.tax);

    document.getElementById('pension').textContent =
        formatCurrency(payroll.pension);

    document.getElementById('nhf').textContent =
        formatCurrency(payroll.nhf);

    document.getElementById('loan').textContent =
        formatCurrency(payroll.loan);

    document.getElementById('otherDeduction').textContent =
        formatCurrency(payroll.other_deduction);

    /*
    |--------------------------------------------------------------------------
    | Net Pay
    |--------------------------------------------------------------------------
    */

    document.getElementById('netPay').textContent =
        formatCurrency(payroll.net_pay);

    /*
    |--------------------------------------------------------------------------
    | Audit
    |--------------------------------------------------------------------------
    */

    document.getElementById('generatedBy').textContent =
        payroll.generated_by?.name ?? '-';

    document.getElementById('approvedBy').textContent =
        payroll.approved_by?.name ?? '-';

    document.getElementById('paidBy').textContent =
        payroll.paid_by?.name ?? '-';

    /*
    |--------------------------------------------------------------------------
    | Download
    |--------------------------------------------------------------------------
    */

    document.getElementById('downloadPayslip').href =
        `/staff/payroll/${payroll.id}/download`;

    /*
    |--------------------------------------------------------------------------
    | Payroll Items
    |--------------------------------------------------------------------------
    */

    let html = '';

    payroll.items.forEach(item => {

        html += `

        <div class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition">

            <div class="flex justify-between items-start">

                <div>

                    <div class="font-semibold text-slate-800">

                        ${item.shift_title}

                    </div>

                    <div class="text-sm text-slate-500 mt-1">

                        ${formatDate(item.shift_date)}

                    </div>

                </div>

                <div class="text-right">

                    <div class="font-semibold">

                        ${formatCurrency(item.amount)}

                    </div>

                    <div class="text-xs text-slate-500">

                        ${item.worked_hours} hrs

                    </div>

                </div>

            </div>

        </div>

        `;

    });

    document.getElementById('payrollItems').innerHTML = html;

}

/*
|--------------------------------------------------------------------------
| Events
|--------------------------------------------------------------------------
*/

document.querySelectorAll('.viewPayroll').forEach(button=>{

    button.addEventListener('click',function(){

        openPayroll(this.dataset.url);

    });

});

closeBackdrop.addEventListener('click',closePayroll);

closeButton.addEventListener('click',closePayroll);

document.addEventListener('keydown',function(e){

    if(e.key === 'Escape'){

        closePayroll();

    }

});

</script>

