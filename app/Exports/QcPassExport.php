<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class QcPassExport implements FromView
{
    private $filter, $cat;

    public function __construct($filter, $cat)
    {
        $this->filter = $filter;
        $this->cat = $cat;
    }

    public function view(): View
    {
        $bln = $this->filter;
        $cat = $this->cat;

        $qc = DB::table('qcpass as a')
        ->join('registrasi as b', 'b.id', '=', 'a.reg_id')
        ->select('a.*', 'b.origin','b.partner_name','b.no_polisi')
        ->where('a.qcpass_tgl','like',$bln.'%')
        ->where('b.jenis',$cat)
        ->get();

        if($cat == "Incoming"){
            return view('qc_download_excel_unload', [
                'data' => $qc
            ]);
        }else{    
            return view('qc_download_excel_load', [
                'data' => $qc
            ]);
        }
    }
}
