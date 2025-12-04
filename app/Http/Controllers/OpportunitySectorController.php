<?php

namespace App\Http\Controllers;

use App\Http\Resources\OpportunityResource;
use App\Http\Resources\OpportunitySectorResource;
use App\Http\Resources\SectorResource;
use App\Models\Opportunity;
use App\Models\OpportunitySector;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OpportunitySectorController extends Controller
{

    public function indexOpportunityBySectorSlug(Request $request){
        $sector = Sector::where('slug', $request->sector_slug)->first();
        $opportunityIds = OpportunitySector::where('sector_id', $sector->id)
                        ->pluck('opportunity_id');
        if(!empty($request->search)) {
            $data = Opportunity::with(['user', 'country', 'opportunity_images', 'sectors'])
                    ->whereIn('id', $opportunityIds)
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->paginate(12);
            return OpportunityResource::collection($data);
        }
        $data = Opportunity::with(['user', 'country', 'opportunity_images', 'sectors'])
                ->whereIn('id', $opportunityIds)
                ->paginate(12);
        return OpportunityResource::collection($data);
    }

    public function indexByOpportunityId($id) {
        $data = OpportunitySector::with(['sector'])->where('opportunity_id', $id)
                ->orderBy('updated_at', 'desc')
                ->get();
        return OpportunitySectorResource::collection($data);
    }

    public function index(){
        $data = OpportunitySector::with(['opportunity', 'sector'])
                ->orderBy('desc', 'updated_at')
                ->paginate(12);
        return OpportunitySectorResource::collection($data);
    }

    public function view($id){
        $data = OpportunitySector::with(['user', 'opportunity', 'sector'])->find($id);
        return new OpportunitySectorResource($data);
    }

    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new OpportunitySector();
        $data->opportunity_id = $request->opportunity_id;
        $data->sector_id = $request->sector_id;
        $data->user_id = $user_id;
        $data->updated_at = now();
        $data->created_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new OpportunitySectorResource($data),
            'message' => 'Data saved successfully.',
        ]);
    }

    public function delete($id){
        OpportunitySector::find($id)->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Deleted successfully.',
        ]);
    }


}
