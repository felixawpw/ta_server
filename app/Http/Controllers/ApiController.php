<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Map, App\Tenant, App\Marker, App\Report, App\User;
use App\Http\Resources\Tenant as TenantCollection;
use App\Http\Resources\Map as MapCollection;
use Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
	public function saveScanPointCalibrationData(Request $request) {
		$marker = Marker::find($request->marker_id);
		$marker->calibrate_x = $request->calibrate_x;
		$marker->calibrate_y = $request->calibrate_y;
		$marker->heading = $request->orientation;
		$marker->save();

		return response()->json([
			"status" => true,
			"message" => "Success calibrating scan point",
		]);

	}

	public function getCalibrateScanPointImage($id) {
		$marker = Marker::find($id);
    	$map = $marker->map;
    	$file = Storage::get($map->original_path);
    	$img = imagecreatefromstring($file);

	    $w = imagesx($img);
	    $h = imagesy($img);

		$image = $this->createScanPointProcessedMap($id, $w, $h);
		header("Content-Type: image");
		return imagepng($image);

	}

	public function createScanPointProcessedMap($id, $w, $h) {
		$marker = Marker::find($id);
		$map = $marker->map;

	    $testImage = imagecreatetruecolor($w, $h);
	    $log = Storage::get("public/image_array/$map->processed_path");
		$rows = explode("\n",$log);

		foreach($rows as $y => $row) {
		    $columns = str_split($row);
		    foreach($columns as $x => $column){
	    	    switch($column){
			        case '1':
			            $color =  imagecolorallocate($testImage,255,255, 255);
	            break;
		        case '0':
		            $color = imagecolorallocate($testImage,0,0,0);
		            break;
		        default:
	            	$color = imagecolorallocate($testImage,125,125,125);
		        }
	        	imagesetpixel($testImage,$x,$y,$color);
	    	}
	    }


    	$x = $marker->point_x;
    	$y = $marker->point_y;

		$markerPng = imagecreatefrompng('scanpoint.png');

		imagecopyresized($testImage, $markerPng, $x - 12, $y - 12, 0, 0, 25, 25, 128, 128);

    	$black = imagecolorallocate($testImage, 0,0,0);
    	$font = "C:\Windows\Fonts\arial.ttf"; 
		imagettftext($testImage, 12, 0, $x-30, $y+30, $black, $font, $marker->name);
	    return $testImage;
	}

	public function getMarkerById($id) {
		$marker = Marker::find($id);

		return response()->json([
			"status" => true,
			"message" => "Success loading marker",
			"markerData" => $marker
		]);
	}


	public function processMarkerReport($id) {
		$marker = Marker::find($id);
		$map = $marker->map;

		return response()->json([
			"status" => true,
			"message" => "Success loading map",
			"mapData" => $map,
			"markerId" => $id
		]);
	}

	public function getReportById($id) {
		$reports = User::find($id)->reports;

		foreach ($reports as $r) {
			$r->tenant_name = $r->tenant->nama;
			$r->marker_name = $r->marker->name;
		}

		return response()->json([
			"status" => true,
			"message" => "Success loading reports",
			"reportData" => $reports
		]);
	}

	public function externalUserLogin(Request $request) {
		$gId = $request->google_auth_id;
		$nama = $request->google_display_name;
		$roles = $request->roles;

		$user = User::where("google_auth_id", "=", $gId)->first();
		if ($user == null) {
			$user = new User;
			$user->google_auth_id = $gId;
			$user->nama = $nama;
			$user->roles = $roles;
			$user->save();
		}

		return response()->json([
			"status" => true,
			"message" => "Log in success",
			"userData" => $user
		]);
	}

	public function storeReport(Request $request) {
		$message = "Success submiting report";
		try {
			$report = new Report;
			$report->report_detail = $request->report_detail;
			$report->report_type = $request->report_type;
			$report->tenant_id = $request->report_tenant_id;
			$report->marker_id = $request->report_marker_id;
			$report->save();
    	} catch (\Exception $e) {
    		$message = $e->getMessage();
    	}

    	return response()->json([
    		"status" => true,
    		"message" => $message
    	]);
	
	}

	public function mapByTenantId($tenantId) {
		$maps = Map::where('tenant_id', '=', $tenantId)->get();

		return response()->json([
			"data" => $maps
		]);
	}

	public function getArrayMapData($mapId) {
		$map = Map::find($mapId);
		return response()->json([
			"status" => true,
			"map_id" => $mapId,
			"data" => Storage::get("public/image_array/$map->processed_path")
		]);
	}

	public function processMap(Request $request, $id) {
		$imageData = $request->image_data;
		$imageArray = $request->image_array;
		$map = Map::find($id);

		$path = explode(".", $map->original_path)[0];
		$fileName = explode("/", $path)[1];

		$dataPath = Storage::put("public/image_data/$fileName.txt", $imageData);
		$arrayPath = Storage::put("public/image_array/$fileName.txt", $imageArray);

		$map->processed_path = "$fileName.txt";
		$map->save();
    	
    	return response()->json([
    		"success" => true
    	]);
	}


	public function tenantByPlacesId($placesId) {
		$tenant = Tenant::where("google_maps_id", "=", $placesId)->with('maps')->first();
		return response()->json([
			"data" => $tenant
		]);
	}

	public function allUnprocessedMap() {
		return response()->json([
			"data" => Map::where('processed_path', '=', null)->with('tenant')->get()
		]);
	}

	public function tenantByUserId($userId) {
		$tenant = Tenant::where("user_id", '=', $userId)->get();

		return response()->json([
			"data" => $tenant
		]);
	}

	public function mapsByMapId($id) {
		$map = Map::find($id);
		$tenant = $map->tenant->maps;
		return response()->json([
			"status" => true,
			"data" => $tenant
		]);
	}

	public function mapJsonById($id) {
		return Map::find($id);
	}

	public function mapJsonByTenant($id) {
		$tenant = Tenant::where('google_maps_id', '=', $id)->first();
		return response()->json([
			"data" => $tenant == null ? "" : $tenant->maps
		]);
	}

	public function tenantJsonById($id) {
		return Tenant::find($id);
	}

	public function tenantJsonAll() {
		return response()->json([
			"data" => Tenant::all()
		]);
	}

    public function originalImageDownload($id) {
    	$map = Map::find($id);
    	return Storage::download($map->original_path);
    }

    public function postTest(Request $request) {
    	return $request;
    }


    public function authenticate(Request $request) {
    	if(Auth::attempt(
    		[
	    		'username' => $request->username,
	    		'password' => $request->password
    		])) {
    		$authenticated = true;
    		$user = Auth::user();
    	}
    	else {
    		$authenticated = false;
    		$user = null;
    	}

    	return response()->json([
    		"authenticated" => $authenticated,
    		"user" => $user
    	]);
    }


    public function getMapInfos($id) {
    	$map = Map::find($id);

    	$image_array = Storage::get("public/image_array/$map->processed_path");
    	$image_data = Storage::get("public/image_data/$map->processed_path");
    	return response()->json([
    		"image_array" => $image_array,
    		"image_data" => $image_data
    	]);
    }

    public function storeMap(Request $request) {
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
	        $message = $status ? "Berhasil proses map" : "Gagal proses map";
    	} catch(\Exception $ex) {
    		$message = $ex->getMessage();
    	}

        return response()->json([
        	"status" => true,
        	"message" => $message
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

	public function getProcessedImage($id) {
    	$map = Map::find($id);
    	$file = Storage::get($map->original_path);
    	$img = imagecreatefromstring($file);

	    $w = imagesx($img);
	    $h = imagesy($img);

		$image = $this->createImageFromProcessedMap($id, $w, $h);
		header("Content-Type: image");
		return imagepng($image);
	}

	public function createImageFromProcessedMap($id, $w, $h) {
			$map = Map::find($id);
		    $testImage = imagecreatetruecolor($w, $h);
		    $log = Storage::get("public/image_array/$map->processed_path");
			$rows = explode("\n",$log);

			foreach($rows as $y => $row) {
					    $columns = str_split($row);
					    foreach($columns as $x => $column){
				    	    switch($column){
						        case '1':
						            $color =  imagecolorallocate($testImage,255,255, 255);
				            break;
				        case '0':
				            $color = imagecolorallocate($testImage,0,0,0);
				            break;
				        default:
			            $color = imagecolorallocate($testImage,125,125,125);
		        }
		        imagesetpixel($testImage,$x,$y,$color);
	    	}
	    }


	    $markers = $map->markers;
	    foreach ($markers as $m) {
			if ($m->marker_type == 4 || $m->marker_type == 5)
				continue;

	    	$x = $m->point_x;
	    	$y = $m->point_y;
			
			if ($m->marker_type == 2 || $m->marker_type == 3)
				$markerPng = imagecreatefrompng("stair.png");
			else if ($m->marker_type == 6)
				$markerPng = imagecreatefrompng("restroom.png");
			else if ($m->marker_type == 7)
				$markerPng = imagecreatefrompng('scanpoint.png');
			else
				$markerPng = imagecreatefrompng('marker.png');

			imagecopyresized($testImage, $markerPng, $x - 12, $y - 12, 0, 0, 25, 25, 128, 128);

	    	$black = imagecolorallocate($testImage, 0,0,0);
	    	$font = "C:\Windows\Fonts\arial.ttf"; 
    		imagettftext($testImage, 12, 0, $x-30, $y+30, $black, $font, $m->name);
	    }
	    return $testImage;
	}
	
	public function generateMarkerQrCode($id) {
		$marker = Marker::find($id);
		$qr = \QrCode::format('png')
			->size(1000)
	        ->generate("scanpoint_marker_$marker->id");
		
		return response($qr)->header('Content-type','image/png');
	}

    public function storeMarker(Request $request) {
    	DB::beginTransaction();
    	$message = "New marker added!";
    	$success = true;
    	try {
	    	$marker = new Marker;
	    	$marker->name = $request->name;
	    	$marker->description = $request->description;
	    	$marker->point_x = $request->point_x;
	    	$marker->point_y = $request->point_y;
	    	$marker->marker_type = $request->marker_type;
	    	$marker->map_id = $request->map_id;
    		$marker->save();

	    	$markerType = $request->marker_type;

	    	if ($markerType == 2 || $markerType == 3) {
	    		$targetedPointX = $request->targeted_point_x;
	    		$targetedPointY = $request->targeted_point_y;
	    		$targetedMapId = $request->targeted_map_id;

	    		$targetMarker = new Marker;
	    		$targetMarker->name = "End of ".$marker->name;
	    		$targetMarker->description = "End of ".$marker->description;
	    		$targetMarker->point_x = $targetedPointX;
	    		$targetMarker->point_y = $targetedPointY;
	    		$targetMarker->marker_type = $markerType == 2 ? 4 : 5;
	    		$targetMarker->map_id = $targetedMapId;
	    		$targetMarker->save();
				$marker->connecting_marker_id = $targetMarker->id;
    			$marker->save();
	    	} else if ($markerType == 7) {
	    		//Store qr code, save to
	    	}
    		DB::commit();
    	} catch (\Exception $e) {
    		$success = false;
    		DB::rollback();
    		$message = $e->getMessage();
    	}


    	return response()->json([
    		"status" => true,
    		"message" => $message,
    		"marker_id" => $success ? $marker->id : "-1"
    	]);
    }

    public function getMarkerByMapId($id) {
    	$map = Map::find($id);
    	return response()->json([
    		"status" => true,
    		"data" => $map->markers
    	]);
    }

	public function getMarkerByTenantId($placesId) {
		$tenant = Tenant::where("google_maps_id", "=", $placesId)->with('maps')->first();
		return response()->json([
			"status" => true,
			"data" => $tenant->markers
		]);
		return $tenant->markers;
	}

}
