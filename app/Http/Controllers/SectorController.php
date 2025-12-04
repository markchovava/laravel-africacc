<?php

namespace App\Http\Controllers;

use App\Http\Resources\SectorResource;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectorController extends Controller
{

    public $upload_location = 'assets/img/sector/';

    public function viewBySlug(Request $request){
        $data = Sector::with(['user', 'opportunities'])
                ->where('slug', $request->slug)->first();
        return new SectorResource($data);
    }

    public function indexAll(){
        $data = Sector::orderBy('name', 'asc')->get();
        return SectorResource::collection($data);
    }

    public function index(Request $request) {
        if($request->search) {
            $data = Sector::with(['user', 'opportunities'])
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return SectorResource::collection($data);
        }
        $data = Sector::with(['user', 'opportunities'])
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return SectorResource::collection($data);
    }

    public function view($id) {
        $data = Sector::with(['user', 'opportunities'])->find($id);
        return new SectorResource($data);
    }

    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new Sector();
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->slug = $request->slug;
        $data->priority = $request->priority;
        if( !empty($request->hasFile('portrait')) ) {
            $portrait = $request->file('portrait');
            $portrait_extension = strtolower($portrait->getClientOriginalExtension());
            $portrait_name = 'sector_p' . date('Yi') . rand(0, 1000) . '.' . $portrait_extension;
            $portrait->move($this->upload_location, $portrait_name);
            $data->portrait = $this->upload_location . $portrait_name;                        
        }
        if( !empty($request->hasFile('landscape')) ) {
            $landscape = $request->file('landscape');
            $landscape_extension = strtolower($landscape->getClientOriginalExtension());
            $landscape_name = 'sector_l' . date('Yi') . date('Yi') . rand(0, 1000) . '.' . $landscape_extension;
            $landscape->move($this->upload_location, $landscape_name);
            $data->landscape = $this->upload_location . $landscape_name;                        
        }
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new SectorResource($data),
            'message' => 'Data successfully saved.',
        ]); 
    }

    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $data = Sector::find($id);
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->slug = $request->slug;
        $data->priority = $request->priority;
        if( $request->hasFile('portrait') ){
            $portrait = $request->file('portrait');
            $portrait_extension = strtolower($portrait->getClientOriginalExtension());
            $portrait_name = 'sector_p' . date('Yi') . rand(0, 1000) . '.' . $portrait_extension;
            if(!empty($data->portrait)){
                if(file_exists( public_path($data->portrait) )){
                    unlink($data->portrait);
                }
                $portrait->move($this->upload_location, $portrait_name);
                $data->portrait = $this->upload_location . $portrait_name;                    
            }else{
                $portrait->move($this->upload_location, $portrait_name);
                $data->portrait = $this->upload_location . $portrait_name;
            }              
        }
        if( $request->hasFile('landscape') ){
            $landscape = $request->file('landscape');
            $landscape_extension = strtolower($landscape->getClientOriginalExtension());
            $landscape_name = 'sector_l' . date('Yi') . rand(0, 1000) . '.' . $landscape_extension;
            if(!empty($data->landscape)){
                if(file_exists( public_path($data->landscape) )){
                    unlink($data->landscape);
                }
                $landscape->move($this->upload_location, $landscape_name);
                $data->landscape = $this->upload_location . $landscape_name;                    
            }else{
                $landscape->move($this->upload_location, $landscape_name);
                $data->landscape = $this->upload_location . $landscape_name;
            }              
        }
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new SectorResource($data),
            'message' => 'Data successfully saved.',
        ]); 
    }

    public function delete($id) {
        $data = Sector::find($id);
        if(isset($data->portrait)){
            if(file_exists( public_path($data->portrait) )){
                unlink($data->portrait);
            }
        }
        if(isset($data->landscape)){
            if(file_exists( public_path($data->landscape) )){
                unlink($data->landscape);
            }
        }
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Deleted successfully.'
        ]);
    }


}
