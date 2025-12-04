<?php

namespace App\Http\Controllers;

use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerController extends Controller
{
    public $upload_location = 'assets/img/partner/';


    public function index(Request $request) {
        if(!empty($request->search)) {
            $data = Partner::with(['user'])
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return PartnerResource::collection($data);
        }
        $data = Partner::with(['user'])
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return PartnerResource::collection($data);
    }

    public function view($id) {
        $data = Partner::with(['user'])->find($id);
        return new PartnerResource($data);
    }

    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new Partner();
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->link = $request->link;
        $data->priority = $request->priority;
        if( !empty($request->hasFile('image')) ) {
            $image = $request->file('image');
            $image_extension = strtolower($image->getClientOriginalExtension());
            $image_name = 'partner_' . date('Yi') . rand(0, 1000) . '.' . $image_extension;
            $image->move($this->upload_location, $image_name);
            $data->image = $this->upload_location . $image_name;                        
        }  
        $data->updated_at = now();
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new PartnerResource($data),
            'message' => 'Data successfully saved.',
        ]);
    }

    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $data = Partner::find($id);
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->link = $request->link;
        $data->priority = $request->priority;
        if( $request->hasFile('image') ){
            $image = $request->file('image');
            $image_extension = strtolower($image->getClientOriginalExtension());
            $image_name = 'partner_' . date('Yi') . rand(0, 1000) . '.' . $image_extension;
            if(!empty($data->image)){
                if(file_exists( public_path($data->image) )){
                    unlink($data->image);
                }
                $image->move($this->upload_location, $image_name);
                $data->image = $this->upload_location . $image_name;                    
            }else{
                $image->move($this->upload_location, $image_name);
                $data->image = $this->upload_location . $image_name;
            }              
        }
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new PartnerResource($data),
            'message' => 'Data successfully saved.',
        ]);
    }

    public function delete($id) {
        $data = Partner::find($id);
        if(isset($data->image)){
            if(file_exists( public_path($data->image) )){
                unlink($data->image);
            }
        }
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Data deleted successfully.',
        ]);
    }

}
