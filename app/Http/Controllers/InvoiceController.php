<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Invoice;
use App\Models\invoices_attachments;
use App\Models\invoices_details;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddInvoice;
use App\Notifications\InvoicePaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\In;
use Maatwebsite\Excel\Facades\Excel;
use Psy\Util\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices=Invoice::all();
        return view('Invoices.invoices',compact('invoices'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections=Section::all();
        return view('invoices.add_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);
        $invoice_id=Invoice::latest()->first()->id;
        invoices_details::create([
            'id_Invoice'=>$invoice_id,
            'invoice_number'=>$request->invoice_number,
            'product'=>$request->product,
            'section'=>$request->Section,
            'status'=>'غير مدفوعة',
            'value_status'=>2,
            'note' => $request->note,
            'user'=>Auth::user()->name

        ]);
        if($request->has('pic')){
            $this->validate($request,['pic'=>'required|mimes:pdf|max:10000'],['pic.mimes','خطأ في حفظ الفاتورة يجب ان يكون الامتداد pdf']);
            $invoice_id=Invoice::latest()->first()->id;
            $image=$request->file('pic');
            $file_name=$image->getClientOriginalName();
            $invoice_number=$request->invoice_number;
            invoices_attachments::create([
                'invoice_id'=>$invoice_id,
                'invoice_number'=>$request->invoice_number,
                'file_name'=>$file_name,
                'created_by'=>Auth::user()->name
            ]);
            $imageName=$request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }
//        $user=User::first();
//        $user->notify(new InvoicePaid($invoice_id));
//        Notification::send($user,new InvoicePaid($invoice_id));
        $user=User::get();
        $invoice_id=Invoice::latest()->first();
        Notification::send($user,new AddInvoice($invoice_id));



        session()->flash('Add','تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices=Invoice::where('id',$id)->first();
        return view('invoices.status_update',compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices=Invoice::where('id',$id)->first();
        $sections=Section::all();
        return view('invoices.edit_invoice',compact('invoices','sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $id=$request->invoice_id;
        Invoice::where('id',$id)->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);
        invoices_details::where('id',$id)->update([
            'invoice_number'=>$request->invoice_number,
            'product'=>$request->product,
            'section'=>$request->Section,
            'note' => $request->note,
            'user'=>Auth::user()->name

        ]);
        session()->flash('Edit','تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
            $id = $request->invoice_id;
            $invoices = Invoice::where('id', $id)->first();
            $attachments=invoices_attachments::where('invoice_id',$id)->first();
            $id_page=$request->id_page;
            if(!$id_page==2) {
                if (!empty($attachments->invoice_number)) {
                    Storage::disk('public_uploads')->deleteDirectory($attachments->invoice_number);
                }
                $invoices->forceDelete();
                session()->flash('Delete');
                return redirect('invoices');
            }
            else {
                $invoices->delete();
                session()->flash('Archive');
                return redirect('/Archive');
            }

    }
    public function getproducts($id){
        $products=DB::table('products')->where('section_id',$id)->pluck('product_name','id');
        return json_encode($products);
    }
    public function status_update($id,Request $request){
        $invoices = Invoice::findOrFail($id);
        if ($request->Status === 'مدفوعة') {
            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            invoices_details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'status' => $request->Status,
                'value_status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            invoices_details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'status' => $request->Status,
                'value_status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');
     }
     public function invoice_paid(){
       $invoices=Invoice::where('Value_Status',1)->get();
       return view('invoices.invoices_paid',compact('invoices'));

     }
    public function invoice_unpaid(){
        $invoices=Invoice::where('Value_Status',2)->get();
        return view('invoices.invoices_unpaid',compact('invoices'));

    }
    public function invoice_partial(){
        $invoices=Invoice::where('Value_Status',3)->get();
        return view('invoices.invoices_partial',compact('invoices'));

    }
    public function print_invoice($id){
        $invoices=Invoice::where('id',$id)->first();
       return view('invoices.print_invoice',compact('invoices'));
    }
    public function export()
    {
        return Excel::download(new InvoicesExport(), 'قائمة الفواتير.xlsx');
    }
    public function markAsRead(){
    $userUnreadAsMark=\auth()->user()->unreadNotifications;
    if($userUnreadAsMark){
        $userUnreadAsMark->markAsRead();
        return back();
    }
    }



}
