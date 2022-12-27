<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Segelbc;
use Illuminate\Http\Request;

class SegelBCController extends BaseController
{
    protected $segel = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Segelbc $segel)
    {
        $this->middleware('auth:api');
        $this->segel = $segel;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $segels = $this->segel->paginate(10);

        return $this->sendResponse($segels, 'Segel list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $segels = $this->segel->pluck('name', 'id');

        return $this->sendResponse($segels, 'Segel list');
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
        $tag = $this->segel->create([
            'nomor' => $request->get('nomor'),
            'keterangan' => $request->get('keterangan'),
        ]);

        return $this->sendResponse($tag, 'Segel Created Successfully');
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
        $tag = $this->segel->findOrFail($id);

        $tag->update($request->all());

        return $this->sendResponse($tag, 'Segel Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $segel = Segelbc::findOrFail($id);
        $segel->delete();

        return $this->sendResponse([$segel], 'User has been Deleted');
    }
}
