<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use Illuminate\Http\Request;

class TruckController extends BaseController
{
    protected $truck = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Truck $truck)
    {
        $this->middleware('auth:api');
        $this->truck = $truck;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trucks = $this->truck->paginate(10);

        return $this->sendResponse($trucks, 'List');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $trucks = $this->truck->pluck('bk', 'id');

        return $this->sendResponse($trucks, 'List');
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
        $truck = $this->truck->create([
            'bk' => strtoupper($request->get('bk')),
            'quota' => $request->get('quota'),
        ]);

        return $this->sendResponse($truck, 'Created Successfully');
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
        $truck = $this->truck->findOrFail($id);

        $truck->update($request->all());

        return $this->sendResponse($truck, 'Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Truck::findOrFail($id);
        $data->delete();

        return $this->sendResponse([$data], 'User has been Deleted');
    }
}
