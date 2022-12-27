<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use App\Models\Method;
use App\Models\Timbangan;
use App\Models\Loading;
use App\Models\Segelbc;
use App\Models\Registrasi;
use App\Models\Config;
use App\Exports\QcPassExport;
use Excel;

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
        return view('home');
    }

    
    public function qc_download_excel($bln,$cat)
    {
        return Excel::download(new QcPassExport($bln,$cat), 'qcpass-'.$bln.'.xlsx');
    }
    public function preview_sj($id)
    {
        $no_revisi = Config::where('key','no_revisi_sj')->first()->value;
        $no_dokumen = Config::where('key','no_dokumen_sj')->first()->value;
        $tgl_berlaku = Config::where('key','tgl_berlaku_sj')->first()->value;

        $reg = Registrasi::find($id);
        $tim = Timbangan::select('bruto','tarra','netto')->where('reg_id',$id)->first();
        $load = Loading::where('reg_id',$id)->first();
        //$segelbc = Segelbc::where('aktif',1)->first();
        if($tim){
            return view('suratjalan',compact('reg','tim','load',
            'no_revisi','no_dokumen','tgl_berlaku'));
        }else{
            return $reg->no_polisi. " Belum ke timbangan atau loading";
        }
    }

    public function preview_slip($id)
    {
        $no_revisi = Config::where('key','no_revisi_slip')->first()->value;
        $no_dokumen = Config::where('key','no_dokumen_slip')->first()->value;
        $tgl_berlaku = Config::where('key','tgl_berlaku_slip')->first()->value;

        $reg = Registrasi::find($id);
        $tim = Timbangan::select('bruto','tarra','netto','tgl_timbang1',
        'tgl_timbang2','usr_timbang1','usr_timbang2')->where('reg_id',$id)->first();
        $load = Loading::where('reg_id',$id)->first();
        //$segelbc = Segelbc::where('aktif',1)->first();
        if($tim){
            return view('sliptimbangan',compact('reg','tim','load',
            'no_revisi','no_dokumen','tgl_berlaku'));
        }else{
            return $reg->no_polisi." sampe ke timbangan";
        }
    }

    public function preview_coa($id)
    {
        $reg = Registrasi::find($id);
        $no_revisi_coa = Config::where('key','no_revisi_coa')->first()->value;
        $no_dokumen_coa = Config::where('key','no_dokumen_coa')->first()->value;
        $tgl_berlaku_coa = Config::where('key','tgl_berlaku_coa')->first()->value;
        $format_surat = Config::where('key','format_surat_coa')->first()->value;

        $ffa = Method::where('param','ffa')->first();
        $mni = Method::where('param','mni')->first();
        $iv = Method::where('param','iv')->first();
        $lc = Method::where('param','lc')->first();
        $melting = Method::where('param','melting')->first();
        $cloud = Method::where('param','cloud')->first();
        $saponifiable = Method::where('param','saponifiable')->first();
        $peroxide = Method::where('param','peroxide')->first();


        $load = Loading::where('reg_id',$id)->first();
        if($load){
            return view('print_coa',compact('reg','load'
            ,'tgl_berlaku_coa','no_dokumen_coa','no_revisi_coa','format_surat'
            ,'ffa','mni','iv','lc','melting','cloud','saponifiable','peroxide'));
        }else{
            return "Data belum sampe ke QC Pass";
        }
    }

    public function test1234()
    {
        // purchase_requisition.name itu nomor kontraknya,
        //  purchase_requisition_line.product_id itu id productnya,
        //   purchase_requisition_line.product_qty itu quantity productnya

        // purchase_requisition.id = purchase_requisition_line.requisition_id


        //product_uom_qty
        $purchase = DB::connection('pgsql_sec')
                    ->table('purchase_requisition as a')
                    ->join('purchase_requisition_line as b', 'b.requisition_id', '=', 'a.id')
                    ->where('b.product_id', 1)
                    // ->where('a.name', '0733/HOLD/CPO-L/N-III/XII/2020')
                    ->where('a.name', '0033/HOLDING/CPO-L/N-III/II/2022')
                    ->select('b.product_id','b.product_qty')
                    ->get();
        dd($purchase);

        return "asdf";

    }
}
