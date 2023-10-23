@extends('layouts.master')
@section('css')
    <!---Internal  Prism css-->
    <link href="{{ URL::asset('assets/plugins/prism/prism.css') }}" rel="stylesheet">
    <!---Internal Input tags css-->
    <link href="{{ URL::asset('assets/plugins/inputtags/inputtags.css') }}" rel="stylesheet">
    <!--- Custom-scroll -->
    <link href="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
@endsection
@section('title')
    تفاصيل فاتورة
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفاصيل الفاتورة</span>
            </div>
        </div>
    </div>

    <!-- breadcrumb -->
@endsection
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif



    @if (session()->has('Delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row row-sm">

        <div class="col-xl-12">
            <!-- div -->
            <div class="card mg-b-20" id="tabs-style2">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="example">

                        <div class="panel panel-primary tabs-style-2">
            <div class=" tab-menu-heading">
                <div class="tabs-menu1">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs main-nav-line">
                        <li><a href="#tab4" class="nav-link active" data-toggle="tab">معلومات الفاتورة</a></li>
                        <li><a href="#tab5" class="nav-link" data-toggle="tab">حالات الدفع</a></li>
                        <li><a href="#tab6" class="nav-link" data-toggle="tab">المرفقات</a></li>
                    </ul>
                </div>
            </div>
            <div class="panel-body tabs-menu-body main-content-body-right border">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab4">
                        <div class="table-responsive mt-15">
                            <table class="table center-aligned-table mb-0 table-hover table-striped"
                                   style="text-align:center">
                                <tr>
                                    <td>رقم الفاتورة</td>
                                    <td>{{$invoice->invoice_number}}</td>
                                    <td>تاريخ الإصدار</td>
                                    <td>{{$invoice->invoice_Date}}</td>
                                    <td>تاريخ الاستحقاق</td>
                                    <td>{{$invoice->Due_date}}</td>
                                    <td>القسم</td>
                                    <td>{{$invoice->section->section_name}}</td>
                                </tr>
                                <tr>
                                    <td>المنتج</td>
                                    <td>{{$invoice->product}}</td>
                                    <td>مبلغ التحصيل</td>
                                    <td>{{$invoice->Amount_collection}}</td>
                                    <td>مبلغ العمولة</td>
                                    <td>{{$invoice->Amount_Commission}}</td>
                                    <td>الخصم</td>
                                    <td>{{$invoice->Discount}}</td>
                                </tr>
                                <tr>
                                    <td>نسبة الضريبة</td>
                                    <td>{{$invoice->Rate_VAT}}</td>
                                    <td>قيمة الضريبة</td>
                                    <td>{{$invoice->Value_VAT}}</td>
                                    <td>الإجمالي مع الضريبة</td>
                                    <td>{{$invoice->Total}}</td>
                                    <td>الحالة الحالية</td>
                                    <td>
                                        @if($invoice->Value_Status==1)
                                            <span
                                                class="badge badge-pill badge-success">{{ $invoice->Status }}</span>
                                        @elseif($invoice->Value_Status==2)
                                            <span
                                                class="badge badge-pill badge-danger">{{ $invoice->Status }}</span>
                                        @else
                                            <span
                                                class="badge badge-pill badge-primary">{{ $invoice->Status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>المستخدم</td>
                                    <td>{{$invoice->section->created_by}}</td>
                                    <td>ملاحظات</td>
                                    <td>{{$invoice->note}}</td>
                                </tr>

                            </table>
                        </div><!-- bd -->

                    </div>
                    <div class="tab-pane" id="tab5">
                        <div class="table-responsive mt-15">
                            <table class="table center-aligned-table mb-0 table-hover table-striped"
                                   style="text-align:center">
                                <tr>
                                    <th>#</th>
                                    <th>رقم الفاتورة</th>
                                    <th>نوع المنتج</th>
                                    <th>القسم</th>
                                    <th>حالة الدفع</th>
                                    <th>تاريخ الدفع</th>
                                    <th>ملاحظات</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>المستخدم</th>
                                </tr>
                                <?php $x=0;?>
                                @foreach($details as $detail)
                                <tr>
                                    <td>{{++$x}}</td>
                                    <td>{{$detail->invoice_number}}</td>
                                    <td>{{$detail->product}}</td>
                                    <td>{{$invoice->section->section_name}}</td>
                                    <td>
                                        @if($detail->value_status==1)
                                            <span
                                                class="badge badge-pill badge-success">{{ $detail->status }}</span>
                                        @elseif($detail->value_status==2)
                                            <span
                                                class="badge badge-pill badge-danger">{{ $detail->status }}</span>
                                        @else
                                            <span
                                                class="badge badge-pill badge-primary">{{ $detail->status }}</span>
                                        @endif
                                    </td>
                                   <td>{{$detail->Payment_Date}}</td>
                                    <td>{{$detail->note}}</td>
                                    <td>{{$invoice->Due_date}}</td>
                                    <td>{{$invoice->section->created_by}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div><!-- bd -->
                    </div>
                    <div class="tab-pane" id="tab6">
                        <div class="card card-statistics">

                                <div class="card-body">
                                    <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                                    <h5 class="card-title">اضافة مرفقات</h5>
                                    <form method="POST" action="{{url('InvoiceAttachments')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile"
                                                   name="file_name" required>
                                            <input type="hidden" id="customFile" name="invoice_number"
                                                   value="{{ $invoice->invoice_number }}">
                                            <input type="hidden" id="invoice_id" name="invoice_id"
                                                   value="{{ $invoice->id }}">
                                            <label class="custom-file-label" for="customFile">حدد
                                                المرفق</label>
                                        </div><br><br>
                                        <button type="submit" class="btn btn-primary btn-sm "
                                                name="uploadedFile">تاكيد</button>
                                    </form>
                                </div>
                        </div>
                        <div class="table-responsive mt-15">
                            <table class="table center-aligned-table mb-0 table-hover table-striped"
                                   style="text-align:center">
                                <tr>
                                    <th>م</th>
                                    <th>اسم الملف</th>
                                    <th>قام بالإضافة</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>العمليات</th>
                                </tr>
                                <?php $x=0?>
                                @foreach($attachments as $attachment)
                                    <tr>
                                        <td>{{++$x}}</td>
                                        <td> {{$attachment->file_name}}</td>
                                        <td>{{$invoice->section->created_by}}</td>
                                        <td>{{$invoice->Due_date}}</td>
                                        <td colspan="3">
                                            <a class="btn btn-outline-success btn-sm"
                                               href="{{ route('view_file',['invoice_number'=>$invoice->invoice_number,'file_name'=>$attachment->file_name])}}"
                                               role="button"><i class="fas fa-eye"></i>&nbsp;
                                                عرض</a>
                                            <a class="btn btn-outline-secondary btn-sm"
                                               href="{{ route('download_file',['invoice_number'=>$invoice->invoice_number,'file_name'=>$attachment->file_name])}}"
                                               role="button"><i class="fas fa-eye"></i>&nbsp;
                                                تحميل</a>
                                                <button class="btn btn-outline-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-file_name="{{ $attachment->file_name }}"
                                                        data-invoice_number="{{ $attachment->invoice_number }}"
                                                        data-id_file="{{ $attachment->id }}"
                                                        data-target="#delete_file">حذف</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div><!-- bd -->

                    </div>
                </div>
            </div>
        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">حذف المرفق</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('delete_file') }}" method="post">
                            @csrf
                            @method('"delete"')
                            <div class="modal-body">
                                <p class="text-center">
                                <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                                </p>

                                <input type="hidden" name="id_file" id="id_file" value="">
                                <input type="hidden" name="file_name" id="file_name" value="">
                                <input type="hidden" name="invoice_number" id="invoice_number" value="">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                                <button type="submit" class="btn btn-danger">تاكيد</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Internal Input tags js-->
    <script src="{{ URL::asset('assets/plugins/inputtags/inputtags.js') }}"></script>
    <!--- Tabs JS-->
    <script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
    <script src="{{ URL::asset('assets/js/tabs.js') }}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.js') }}"></script>
    <!-- Internal Prism js-->
    <script src="{{ URL::asset('assets/plugins/prism/prism.js') }}"></script>

    <script>
        $('#delete_file').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id_file = button.data('id_file')
            var file_name = button.data('file_name')
            var invoice_number = button.data('invoice_number')
            var modal = $(this)

            modal.find('.modal-body #id_file').val(id_file);
            modal.find('.modal-body #file_name').val(file_name);
            modal.find('.modal-body #invoice_number').val(invoice_number);
        })

    </script>

    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

    </script>

@endsection
