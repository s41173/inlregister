<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Products\ProductRequest;
use App\Models\Registrasi;
use App\Models\Supir;
use App\Models\Tag;
use App\Models\Timbangan;
use App\Models\Qcpass;
use App\Models\Unloading;
use App\Models\Loading;
use Illuminate\Http\Request;
use DB;
use Session;

class RegistrasiController extends BaseController
{

    protected $product = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Registrasi $product)
    {
        $this->middleware('auth:api');
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $register = Registrasi::orderby('id', 'desc')->paginate(10);

        return $this->sendResponse($register, 'Register list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Products\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $filterByBK = Registrasi::where("no_polisi",$id)->get();
        if(count($filterByBK) == 0){
            $filterByBK = Registrasi::where("rfid",$id)->get();            
        }

        return $this->sendResponse($filterByBK, 'Reg Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function dashboard_counter()
    {
        //$reg = Registrasi::get();

        $data['registrasi'] = 0;
        $data['timbangan'] = 0;
        $data['qcpass'] = 0;
        return $this->sendResponse($data, 'Total');
    }

    public function register_src_number(Request $request){
        $dt = $request->all();
        $no = $request->get('no');

        if($no){

            // Validasi No Kontrak dengan status IN
            // $CheckQty = Registrasi::
            //     where("no_contract",$no)
            //     ->first();
            // if($CheckQty){
            //     if($CheckQty->type == "IN" AND $CheckQty->jenis == "Outgoing"){
            //         $res['status'] = false;
            //         $res['message'] = 'Tidak dapat input, No. Kontrak sedang proses dengan '. $CheckQty->no_polisi;
            //         return response()->json($res, 200);    
            //     }
            // }

            // Validasi Quota
            // $CheckQty = Registrasi::
            //     where("no_contract",$no)
            //     ->get();
            // if(count($CheckQty) > 0){
                
            //     $Netto = 0;
            //     foreach($CheckQty as $rQty){
            //         $getNetto = Timbangan::select('netto')->where('reg_id',$rQty->id)->first();
            //         $Netto += $getNetto->netto;
            //     }
            //     $uom_qty = Registrasi::where("no_contract",$no)
            //     ->orderby('product_uom_qty')
            //     ->first();
            //     if($uom_qty){
            //         if($Netto >= $uom_qty->product_uom_qty){
            //             $res['status'] = false;
            //             $res['message'] = 'No. Kontrak Quanty sudah full';
            //             return response()->json($res, 200);
            //         }        
            //     }                
            // }

            // Cari No Kontrak 
            $reg_contract = DB::connection('pgsql_sec')->table('stock_picking as a')
                ->join('stock_move as move', 'move.picking_id', '=', 'a.id')
                ->join('product_product as pp', 'pp.id', '=', 'move.product_id')
                ->join('product_template as pt', 'pt.id', '=', 'pp.product_tmpl_id')
                ->join('res_partner as pn', 'pn.id', '=', 'a.partner_id')
                ->select('a.id as picking_id', 'a.origin', 'a.no_po', 'a.no_contract',
                'a.name as picking_name','a.jenis_barang','a.no_dokumen_do','a.no_do','a.no_aju',
                'a.jenis_dokumen','a.no_dokumen','a.no_segel_bc',
                'move.product_id','move.product_uom as product_uom_id','move.location_id','move.location_dest_id',
                'move.id as stock_move_id', 'move.product_uom_qty',
                'pp.default_code',
                'pt.name as product_name',
                'pn.name as partner_name','pn.contact_address_complete as alamat')
                ->where('a.no_contract', $no)
                ->where('a.state', "ready")
                //->orWhere('a.name', $no)
                //->orWhere('a.origin', $no)
                // ->whereNull('a.no_dokumen')
                // ->whereNull('a.no_segel_bc')
                // ->whereNull('a.no_aju')
                // ->whereNull('a.jenis_dokumen')
                ->get();
            if(count($reg_contract) > 0){
                $res['status'] = true;
                $res['message'] = 'Success';
                $res['data'] = $reg_contract;
                return response()->json($res, 200);
            }else{
                $res['status'] = false;
                $res['message'] = 'No.Kontrak tidak ditemukan';
                $res['data'] = null;
                return response()->json($res, 200);
            }
        }else{
                $res['status'] = false;
                $res['message'] = 'Parameter tidak diterima';
                return response()->json($res, 200);
        }
    }


    public function register_out($id)
    {
        $reg = Registrasi::find($id);
        $reg->tgl_keluar_truk = date("Y-m-d h:i:s");
        $reg->type = "OUT";
        $reg->update_by = 1;
        $reg->rfid = null;
        $reg->update_in = date("Y-m-d h:i:s");
  
        //Sending to Odoo
        if($reg->jenis == "Incoming"){
            $this->Incoming("Insert", $id);
        }else{
            $this->Outgoing("Insert", $id);
        }
        $reg->save();
        
        return $this->sendResponse($reg, 'Cars has been Out');
    }

    //
    public function register_send(Request $request){

        $reg = $request->all();

        $user_login = Session::get('login_id');
        $user_name = Session::get('login_user');
        $proses = "in"; //In or Out
        $tgl = date('Y-m-d h:i:s');
        $no_polisi = trim(strtoupper($request->get('no_polisi')));
        $driver_name = trim(strtoupper($request->get('driver_name')));
        $transporter = trim(strtoupper($request->get('transporter')));

        $validation_in = $request->get('jenis');
        if($validation_in == 'Incoming'){
            $validated = $request->validate([
                'picking_id' => 'required',
                'picking_name' => 'required',
                'product_id' => 'required',
                'product_uom_id' => 'required',
                'location_id' => 'required',
                'location_dest_id' => 'required',
                'nama_kendaraan' => 'required',
                'no_polisi' => 'required',
                'transporter' => 'required',
                'driver_name' => 'required',
                'jenis_material' => 'required',
                'jenis' => 'required',
                'bruto_from' => 'required',
                'tarra_from' => 'required',
                'netto_from' => 'required',
            ]);
        }else if($validation_in == 'Outgoing'){
            $validated = $request->validate([
                'picking_id' => 'required',
                'picking_name' => 'required',
                'product_id' => 'required',
                'product_uom_id' => 'required',
                'location_id' => 'required',
                'location_dest_id' => 'required',
                'nama_kendaraan' => 'required',
                'no_polisi' => 'required',
                'transporter' => 'required',
                'driver_name' => 'required',
                'jenis_material' => 'required',
                'jenis' => 'required',
                'no_do' => 'required',
                'destination' => 'required',
            ]);
        }else{
            $validated = $request->validate([
                'picking_id' => 'required',
                'picking_name' => 'required',
                'product_id' => 'required',
                'product_uom_id' => 'required',
                'location_id' => 'required',
                'location_dest_id' => 'required',
                'nama_kendaraan' => 'required',
                'no_polisi' => 'required',
                'transporter' => 'required',
                'driver_name' => 'required',
                'jenis_material' => 'required',
                'jenis' => 'required',
            ]);
    
        }


        unset($reg['proses']);
        unset($reg['keterangan']);

        $cekSupir = Supir::where('supir',$driver_name)
        ->where('no_polisi',$no_polisi)
        ->where('ban','Tidak/Sopir Melanggar')
        ->first();
        if($cekSupir){
            $res['status'] = false;
            $res['message'] = "Gagal!, Supir Pelanggan..,".$cekSupir->ket;
            return response()->json($res, 200);
        }else{
            $spr['no_polisi'] = $no_polisi;
            $spr['supir'] = $driver_name;
            $spr['transporter'] = $transporter;
            $spr['ban'] = 'Aktif';
            Supir::updateOrInsert($spr);
        }

        if($proses == "in"){
            
            $getType = Registrasi::where("no_polisi", $no_polisi)
                ->where('type','IN')->first();
            if($getType){
                $res['status'] = false;
                $res['message'] = "Gagal!, Kendaraan ".$no_polisi." belum keluar!";
                return response()->json($res, 200);
            }

            $tglFilter = date("Y-m-d");
            $getNomor = Registrasi::where("create_in", 'like', $tglFilter.'%')->get();
            if(count($getNomor) > 0){
                $nourut = count($getNomor) + 1;
            }else{
                $nourut = 1;
            }
            unset($spr['driver_name']);
            unset($spr['no_polisi']);

            $reg['tgl_entry'] = date('Y-m-d');
            $reg['no_ticket'] = $nourut; 
            $reg['type'] = "IN";     
            $reg['create_by'] = 1;
            $reg['create_in'] = $tgl;
            $reg['driver_name'] = $driver_name;
            $reg['no_polisi'] = $no_polisi;
            $reg['tgl_masuk_truk'] = $tgl;
            $reg['asal_pks'] = $reg['partner_name'];
    
            unset($reg['user_login']);
            $getID = Registrasi::insertGetId($reg);
    
            $res['status'] = true;
            $res['message'] = "Register Success !";
            $res['data'] = $getID;
            return response()->json($res, 200);
        }
    }


    // Odoo Incoming / Unloading
    public function Incoming($if, $regid){
       if($if == "Insert"){
        $getReg = Registrasi::whereNull("migrasi")->find($regid);
        if($getReg){
            
            $getReg->migrasi = 1;
            $getReg->save();

            $this->stock_picking_truck($regid);
            }
        }
    }
   
    // Odoo Outgoing / Loading
    public function Outgoing($if, $regid){
        if($if == "Insert"){
        $getReg = Registrasi::whereNull("migrasi")->find($regid);
        if($getReg){
            
            $getReg->migrasi = 1;
            $getReg->save();

            $this->stock_picking_truck($regid);
            }
        }
    }

    public function stock_picking_truck($regid){

        $getReg = Registrasi::find($regid);
        $QcPass = Qcpass::where('reg_id',$regid)->first();
        $Weight = Timbangan::where('reg_id',$regid)->first();
        $load = Loading::where('reg_id',$regid)->first();

        $ffa = null;
        $mni = null;
        $imp = null;
        $iv = null;
        $lc = null;

        $bruto = 0;
        $tarra = 0;
        $netto = 0;
        $netto_diff = 0;
        $netto_diff_persen = 0;


        $segel1 = null;
        $segel2 = null;
        $segel3 = null;

        if($Weight){
            $bruto = $Weight->bruto;
            $tarra = $Weight->tarra;
            $netto = $Weight->netto;
            $netto_diff = $Weight->netto_diff;
            $netto_diff_persen = $Weight->netto_diff_persen;
        }

        if($getReg){
            if($getReg->type == "Incoming"){
                if($QcPass){
                    $ffa = $QcPass->ffa;
                    $mni = $QcPass->mni;
                    $imp = $QcPass->imp;
                    $iv = $QcPass->iv;
                }
            } else {
                if($load){
                    $ffa = $load->ffa;
                    $mni = $load->mni;
                    $imp = $load->imp;
                    $iv = $load->iv;
                    $lc = $load->lc;

                    $segel1 = $load->segel1;
                    $segel2 = $load->segel2;
                    $segel3 = $load->segel3;
                }
            }
        }

        $truck['picking_id'] = $getReg->picking_id;
        $truck['picking_name'] = $getReg->picking_name;
        $truck['product_id'] = $getReg->product_id; 
        $truck['product_uom_id'] = $getReg->product_uom_id;
        $truck['location_id'] = $getReg->location_id;
        $truck['location_dest_id'] = $getReg->location_dest_id;

        $truck['sequence'] = $getReg->no_ticket;
        $truck['do'] = $getReg->no_do;
        $truck['no_do'] = $getReg->no_do;
        $truck['nama_kendaraan'] = $getReg->nama_kendaraan;
        $truck['no_container'] = $getReg->no_container;
        $truck['no_polisi'] = $getReg->no_polisi;
        $truck['transporter'] = $getReg->transporter;
        $truck['driver_name'] = $getReg->driver_name;
        $truck['asal_pks'] = $getReg->partner_name;
        $truck['destination'] = $getReg->destination;
        $truck['partner_name'] = $getReg->partner_name;
        $truck['no_karcis_timbangan'] = $getReg->no_ticket;
        $truck['no_surat_jalan'] = $getReg->no_surat_jalan;

        $truck['tgl_masuk_truk'] = $getReg->tgl_masuk_truk;
        $truck['tgl_keluar_truk'] = date("Y-m-d h:i:s");

        $truck['bruto'] = $bruto;
        $truck['tarra'] = $tarra;
        $truck['qty_done'] = $netto;
        $truck['netto_diff'] = $netto_diff;
        $truck['netto_diff_persen'] = $netto_diff_persen;

        $truck['ffa'] = $ffa;
        $truck['mni'] = $mni;
        $truck['imp'] = $imp;
        $truck['iv'] = $iv;
        $truck['mpt_degrees'] = null;
        $truck['color'] = null;

        //$truck['lc'] = $lc;
        //$truck['other'] = null; //+odoo
        $truck['bruto_from'] = $getReg->bruto_from;
        $truck['tarra_from'] = $getReg->tarra_from;
        $truck['netto_from'] = $getReg->netto_from;

        $truck['no_segel1'] = $segel1;
        $truck['no_segel2'] = $segel2;
        $truck['no_segel3'] = $segel3;
        //$truck['no_segel4'] = null;//+odoo

        $truck['origin'] = $getReg->origin;
        $truck['qty_box'] = null;
        $truck['create_uid'] = 1;
        $truck['write_uid'] = 1;
        $truck['create_date'] = date('Y-m-d h:i:s');
        $truck['write_date'] = date('Y-m-d h:i:s');
        DB::connection('pgsql_sec')
            ->table('stock_picking_truck')
            ->insert($truck);
    }

}
