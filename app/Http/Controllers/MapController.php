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
        $message = "New map added!";
        try {
            $file = $request->file("denah");

            $map = new Map;
            
            $map->nama = $request->nama;
            $map->tenant_id = $request->tenant_id;
            $map->deskripsi = $request->deskripsi;
            $map->original_path = Storage::putFile("public", $file);
            $map->height = $request->height;
            $map->scale_width = $request->scale_width;
            $map->scale_length = $request->scale_length;

            $map->save();

            $status = $this->tryProcessImage($map->id);
            $message = $status ? $message : "Unexpected error occured.";
        } catch(\Exception $ex) {
            $message = $ex->getMessage();
        }

        return response()->json([
            "status" => true,
            "message" => $message
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
        $message = "Map updated!";
        try {
            $map = Map::find($id);
            $message = "Map updated!";
            
            $map->nama = $request->nama;
            $map->tenant_id = $request->tenant_id;
            $map->deskripsi = $request->deskripsi;
            $map->height = $request->height;
            $map->scale_width = $request->scale_width;
            $map->scale_length = $request->scale_length;
            $map->save();
            $file = $request->file("denah");

            if ($request->hasFile("denah")) {
                $file = $request->file("denah");
                $map->original_path = Storage::putFile("public", $file);
                $map->save();
                $status = $this->tryProcessImage($map->id);
            }
        } catch(\Exception $ex) {
            $message = $ex->getMessage();
        }

        return response()->json([
            "status" => true,
            "message" => $message
        ]);

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
        Map::destroy($id);
        return response()->json([
            "status" => true,
            "message" => "Data deleted"
        ]);

    }

    public function tryProcessImage($id) {
        $map = Map::find($id);
        $file = Storage::get($map->original_path);
        $img = imagecreatefromstring($file);

        $w = imagesx($img);
        $h = imagesy($img);
        
        $processedMap = array();

        $fp = fopen("text.txt", 'w');

        $r = $g = $b = 0;
        $toMatchR = $toMatchG = $toMatchB = 0;
        for($y = 0; $y < $h; $y++) {
            for($x = 0; $x < $w; $x++) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                if ($this->isMatch($r, $g, $b, $toMatchR, $toMatchG, $toMatchB))
                    fwrite($fp, 0);
                else
                    fwrite($fp, 1);
            }
            fwrite($fp, "\n");
        }
        fclose($fp);

        $path = explode(".", $map->original_path)[0];
        $fileName = explode("/", $path)[1];
        
        Storage::put("public/image_array/$fileName.txt", file_get_contents("text.txt"));
        $map->processed_path = "$fileName.txt";
        $map->save();

       //  $image = $this->createImageFromProcessedMap($map->id, $w, $h);
        return 1;  
    }

    public function isMatch($r, $g, $b, $toMatchR, $toMatchG, $toMatchB) {
        $delta = 100;

        return abs($r - $toMatchR) <= 150
            && abs($g - $toMatchG <= 150)
            && abs($b - $toMatchB <= 150);
    }

}
