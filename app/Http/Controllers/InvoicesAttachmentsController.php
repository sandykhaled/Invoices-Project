<?php

namespace App\Http\Controllers;

use App\Models\invoices_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicesAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file_name'=>'mimes:jpg,png,pdf,jpeg'
        ],['file_name.mimes'=>'يجب ان يكون المرفق في صيغة pdf, jpg, jpeg, png']);
        $image=$request['file_name'];
        $file_name = $image->getClientOriginalName();
        invoices_attachments::create([
            'file_name'=>$file_name,
            'invoice_number'=>$request->invoice_number,
            'invoice_id'=>$request->invoice_id,
            'created_by'=>Auth::user()->name
        ]);
        $imageName = $request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('Attachments/'. $request->invoice_number), $imageName);
        session()->flash('Add','تم إضافة المرفق بنجاح');
        return back();


    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices_attachments $invoices_attachments)
    {
        //
    }
}
