<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tenant;
use Illuminate\Support\Facades\Input;

class TenantController extends Controller
{

    public function getByAddress(Request $request) {
        // if (req::has('data')) {
        //     $data = Input::get('data');
        //     $tenant = Tenant::where("nama", 'like', "%$data%")->get();
        //     return response()->json([
        //         "message" => "success",
        //         "data" => $tenant
        //     ]);
        // }
        $data = $request->nama;
        return response()->json([
            "message" => true,
            "data" => Tenant::where("nama", 'like', "%$data%")->get()
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tenant = new Tenant;
        $tenant->nama = $request->nama;
        $tenant->user_id = $request->user_id;
        $tenant->google_maps_id = $request->google_maps_id;
        $tenant->google_maps_address = $request->google_maps_address;
        $message = "Success add new place";
        try {
            $tenant->save();
        } catch (\Exception $ex) {
            $message = $ex.getMessage();
        }

        return response()->json([
            "status" => true,
            "message" => $message,
            "tenant_id" => $tenant->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
