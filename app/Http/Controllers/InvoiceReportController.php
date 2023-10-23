<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use function Laravel\Prompts\select;

class InvoiceReportController extends Controller
{
    public function index(){
        return view('reports.invoices_reports');
    }
    public function search_invoices(Request $request){
        $rdio=$request->rdio;
        if($rdio==1){
            if($request->type && $request->start_at =='' && $request->end_at == ''){
                $invoices=Invoice::select('*')->where('Status','=',$request->type)->get();
                $type=$request->type;
                return view('invoices.invoices',compact('type'))->withDetails($invoices);
            }
            else{
                $start_at=date($request->start_at);
                $end_at=date($request->end_at);
                $type=$request->type;
                $invoices=Invoice::whereBetween('Invoice_Date',[$start_at,$end_at])->where('Status','=',$request->type)->get();
                return view('reports.invoices_reports',compact('start_at','end_at','type'))->withDetails($invoices);

            }
        }
        else{
            $invoices=Invoice::select('*')->where('invoice_number','=',$request->invoice_number)->get();
            return view('reports.invoices_reports')->withDetails($invoices) ;

        }

    }
}
