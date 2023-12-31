<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;

class InvoicesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Invoice::select('invoice_number','invoice_date','Due_date','product','Amount_collection','Amount_Commission','Rate_VAT','Value_VAT','Total','Status','note','Payment_Date')->get();
    }
}
