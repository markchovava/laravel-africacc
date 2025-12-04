<?php

namespace App\Http\Controllers;

use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use App\Models\OpportunityImage;
use App\Models\OpportunitySector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpportunityController extends Controller
{
    public $upload_location = 'assets/img/opportunity/';

    public function statusUpdate(Request $request) {
        $data = Opportunity::find($request->id);
        $data->status = $request->status;
        $data->save();
        /* RESPONSE */
        return response()->json([
            'status' => 1,
            'data' => new OpportunityResource($data),
            'message' => 'Data successfully updated.',
        ]); 
    }

    public function viewBySlug(Request $request){
        $data = Opportunity::with(['user', 'country', 'sectors', 'opportunity_images'])->
                where('slug', $request->slug)->first();
        return new OpportunityResource($data);
    }

    public function indexByNum(Request $request) {
        $data = Opportunity::with(['user', 'country', 'sectors', 'opportunity_images'])
                ->orderBy('priority', 'asc')
                ->orderBy('updated_at', 'desc')
                ->paginate($request->num);
        return OpportunityResource::collection($data);
    }

    public function index(Request $request) {
        if($request->search) {
            $data = Opportunity::with(['user', 'country', 'sectors', 'opportunity_images'])
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return OpportunityResource::collection($data);
        }
        $data = Opportunity::with(['user', 'country', 'sectors', 'opportunity_images'])
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return OpportunityResource::collection($data);
    }

    public function view($id) {
        $data = Opportunity::with(['user', 'country', 'opportunity_images', 'sectors'])->find($id);
        return new OpportunityResource($data);
    }

    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new Opportunity();
        $data->user_id = $user_id;
        $data->country_id = $request->country_id;
        $data->name = $request->name;
        $data->slug = $request->slug;
        $data->priority = $request->priority;
        $data->status = 'Available';
        $data->description = $request->description;
        $data->short_description = $request->short_description;
        $data->amount = $request->amount;
        $data->expected_return = $request->expected_return;
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        /*  */
        if( !empty($request->file('opportunity_images')) ){
            $opportunity_images = $request->file('opportunity_images');
            for($i = 0; $i < count($opportunity_images); $i++){
                $item = new OpportunityImage();
                $item->opportunity_id = $data->id;
                $item->user_id = $user_id;
                if( isset($opportunity_images[$i]) ) {
                    $image = $opportunity_images[$i];
                    $image_extension = strtolower($image->getClientOriginalExtension());
                    $image_name = 'opportunity_' . date('Ymh') . rand(0, 10000) . '.' . $image_extension;
                    $image->move($this->upload_location, $image_name);
                    $item->image = $this->upload_location . $image_name;                        
                }
                $item->created_at = now();
                $item->updated_at = now();
                $item->save();
            }
        }
        /* RESPONSE */
        return response()->json([
            'status' => 1,
            'data' => new OpportunityResource($data),
            'message' => 'Data successfully saved.',
        ]); 
    }

    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $data = Opportunity::find($id);
        $data->user_id = $user_id;
        $data->country_id = $request->country_id;
        $data->name = $request->name;
        $data->slug = $request->slug;
        $data->priority = $request->priority;
        $data->description = $request->description;
        $data->short_description = $request->short_description;
        $data->amount = $request->amount;
        $data->expected_return = $request->expected_return;
        /*  */
        if(!empty($request->file('opportunity_images'))){
            $opportunity_images = $request->file('opportunity_images');
            for($i = 0; $i < count($opportunity_images); $i++){
                $item = new OpportunityImage();
                $item->opportunity_id = $data->id;
                $item->user_id = $user_id;
                if( isset($opportunity_images[$i]) ) {
                    $image = $opportunity_images[$i];
                    $image_extension = strtolower($image->getClientOriginalExtension());
                    $image_name = 'opportunity_' . date('Ymh') . rand(0, 10000) . '.' . $image_extension;
                    $image->move($this->upload_location, $image_name);
                    $item->image = $this->upload_location . $image_name;                        
                }
                $item->created_at = now();
                $item->updated_at = now();
                $item->save();
            }
        }
        $data->updated_at = now();
        $data->save();
        /* RESPONSE */
        return response()->json([
            'status' => 1,
            'data' => new OpportunityResource($data),
            'message' => 'Data successfully saved.',
        ]); 
    }

    public function delete($id){
        $opportunity_images = OpportunityImage::where('opportunity_id', $id)->get();
        if(!empty($opportunity_images)) {
            for($i = 0; $i < count($opportunity_images); $i++){
                if(file_exists( public_path($opportunity_images[$i]['image']) )){
                    unlink($opportunity_images[$i]['image']);
                }
            }
            OpportunityImage::where('opportunity_id', $id)->delete();
        }
        $opportunity_sector = OpportunitySector::where('opportunity_id', $id);
        if(isset($opportunity_sector)) {
            $opportunity_sector->delete();
        }
        $data = Opportunity::find($id);
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Deleted successfully.',
        ]);
    }


}
