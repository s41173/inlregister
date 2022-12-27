<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Supir;
use Illuminate\Http\Request;

class DriverController extends BaseController
{
    protected $driver = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Supir $driver)
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
        $segels = $this->driver->paginate(10);

        return $this->sendResponse($segels, 'List');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $segels = $this->driver->pluck('no_polisi','supir', 'id');

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
        $tag = $this->driver->create([
            'supir' => $request->get('supir'),
            'no_polisi' => $request->get('no_polisi'),
            'transporter' => $request->get('transporter'),
            'ket' => $request->get('ket'),
            'no_surat' => $request->get('no_surat'),
        ]);

        return $this->sendResponse($tag, 'Created Successfully');
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
        $tag = $this->driver->findOrFail($id);

        $tag->update($request->all());

        return $this->sendResponse($tag, 'Has been updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Supir::findOrFail($id);
        $user->delete();

        return $this->sendResponse([$user], 'User has been Deleted');
    }
}
