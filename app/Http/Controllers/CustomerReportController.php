<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;

class CustomerReportController extends Controller
{
    public function index(){
       $sections=Section::all();
       $products=Product::all();
       return view('reports.customers_reports',compact('sections','products'));
    }
    public function search(Request $request){
      if($request->Section && $request->product && $request->start_at == '' && $request->end_at == ''){
          $invoices=Invoice::select('*')->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
          $sections=Section::all();
          return view('reports.customers_reports',compact('sections'))->withDetails($invoices);
      }
      else{
          $start_at=date($request->start_at);
          $end_at=date($request->end_at);
          $invoices=Invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
          $sections=Section::all();
          return view('reports.customers_reports',compact('sections'))->withDetails($invoices);
      }

    }
}
