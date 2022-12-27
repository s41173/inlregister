<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Supir;
use App\Models\Qcpass;
use Illuminate\Http\Request;
use DB;

class QcpassController extends BaseController
{
    protected $driver = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Qcpass $driver)
    {
        $this->middleware('auth:api');
        $this->driver = $driver;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $segels = DB::table('qcpass as a')
        // ->join('registrasi as b', 'b.id', '=', 'a.reg_id')
        // ->select('a.*', 'b.origin','b.partner_name','b.no_polisi')
        // ->paginate(10);

        $unload = DB::table('qcpass as a')
        ->join('registrasi as b', 'b.id', '=', 'a.reg_id')
        ->select('a.ffa','a.mni','a.iv','a.lc','a.qcpass_tgl as tgl'
        ,'b.origin','b.partner_name','b.no_polisi','b.jenis');

        $load = DB::table('load as a')
        ->join('registrasi as b', 'b.id', '=', 'a.reg_id')
        ->select('a.ffa','a.mni','a.iv','a.lc','a.loading_tgl as tgl',
        'b.origin','b.partner_name','b.no_polisi','b.jenis')
        ->union($unload)
        ->paginate(10);
        $result = $load;

        return $this->sendResponse($result, 'List');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($bln)
    {
        $exp = explode("=",$bln);

        $date = $exp[0];
        $cat = $exp[1];

        $unload = DB::table('qcpass as a')
        ->join('registrasi as b', 'b.id', '=', 'a.reg_id')
        ->select('a.ffa','a.mni','a.iv','a.lc','a.qcpass_tgl as tgl',
        'b.origin','b.partner_name','b.no_polisi','b.jenis')
        ->where('a.qcpass_tgl','like',$date.'%')
        ->where('b.jenis',$cat);

        $load = DB::table('load as a')
        ->join('registrasi as b', 'b.id', '=', 'a.reg_id')
        ->select('a.ffa','a.mni','a.iv','a.lc','a.loading_tgl as tgl',
        'b.origin','b.partner_name','b.no_polisi','b.jenis')
        ->where('a.loading_tgl','like',$date.'%')
        ->where('b.jenis',$cat)
        ->union($unload)
        ->get();
        $result = $load;

        return $this->sendResponse($result, 'List');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $segels = $this->driver->pluck('reg_id','ffa', 'mni');
        return $this->sendResponse($segels, 'List');
    }


    /**
     * Store a newly created resource in storage.
     *
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
    }

    /**
     * Update the resource in storage
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
