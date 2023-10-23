<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $count_all =Invoice::count();
        $count_invoices1 = Invoice::where('Value_Status', 1)->count();
        $count_invoices2 = Invoice::where('Value_Status', 2)->count();
        $count_invoices3 = Invoice::where('Value_Status', 3)->count();

        if($count_invoices2 == 0){
            $nspainvoices2=0;
        }
        else{
            $nspainvoices2 = $count_invoices2/ $count_all*100;
        }

        if($count_invoices1 == 0){
            $nspainvoices1=0;
        }
        else{
            $nspainvoices1 = $count_invoices1/ $count_all*100;
        }

        if($count_invoices3 == 0){
            $nspainvoices3=0;
        }
        else{
            $nspainvoices3 = $count_invoices3/ $count_all*100;
        }
        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
               ['label'=>'نسبة الفواتير' ,
                   'backgroundColor'=>['#FF8080','#90C8AC','#F9B572'],
                   'borderColor'=>'rgba(38,185,154,0.7)',
                   'pointBorderColor'=>'rgba(38,185,154,0.7)',
                   'pointBackgroundColor'=>'rgba(38,185,154,0.7)',
                   'pointHoverBackgroundColor'=>'#fff',
                   'pointHoverBorderColor'=>'rgba(220,220,220,1)',
                   'data'=>[$nspainvoices2,$nspainvoices1,$nspainvoices3]



           ]
            ])
            ->optionsRaw([
                'legend'=>[
                    'display'=>true,
                    'labels'=>[
                        'fontColor'=>'black',
                        'fontFamily'=>'Cairo'
                        ,'fontStyle'=>'bold',
                        'fontSize'=>14
                    ]
                ]
            ]);
        $chartjs2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#FF8080','#90C8AC','#F9B572'],
                    'data' => [$nspainvoices2, $nspainvoices1,$nspainvoices3]
                ]
            ])
            ->options([]);


        return view('home', compact('chartjs','chartjs2'));

    }
}
