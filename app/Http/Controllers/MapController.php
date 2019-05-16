<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Map;
class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $maps = Map::all();
        return view("map.index", compact("maps")); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("map.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $nama = $request->nama;
        $deskripsi = $request->deskripsi_denah;
        $file = $request->file("denah");
        
        $map = new Map;
        
        $map->nama = $nama;
        $map->tenant_id = 1;
        $map->deskripsi = $deskripsi;
        $map->original_path = Storage::putFile("public", $file);
        $map->height = $request->height;
        $map->scale_width = $request->scale_width;
        $map->scale_length = $request->scale_length;

        $map->save();
        $status = "1||Success||Berhasil menambahkan denah baru : $map->nama";

        return redirect()->action("MapController@index")->with("status", $status);
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
        $map = Map::find($id);
        return view("map.show", compact('map'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view("map.edit");
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
