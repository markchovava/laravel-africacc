<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvestmentResource;
use App\Http\Resources\OpportunityResource;
use App\Models\Investment;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InvestmentController extends Controller
{

    public function investmentOpportunityView(Request $request){
        $investment = Investment::find($request->investment_id);
        if(!isset($investment)){
            return response()->json([
                'status' => 0,
                'message' => 'Data not found.'
            ]);
        }
        $data = Opportunity::with(['country', 'sectors'])
                ->find($investment->opportunity_id);
        return response()->json([
            'data' => new OpportunityResource($data),
        ]);
    }
    
    public function statusUpdate(Request $request) {
        $data = Investment::find($request->investment_id);
        $data->status = $request->status;
        $data->updated_at = now();
        $data->save();
        /*  */
        return response()->json([
            'status' => 1,
            'message' => 'Status updated successfully.',
            'data' => new InvestmentResource($data),
        ]);
    }

    public function indexByUser(Request $request){
        $user_id = Auth::user()->id;
        if(!empty($request->search)){
            $opportunityIds = Investment::where('user_id', $user_id)->pluck('opportunity_id');
            $data = Opportunity::with(['investment'])
                    ->whereIn('id', $opportunityIds)
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return OpportunityResource::collection($data);
        }
        $opportunityIds = Investment::where('user_id', $user_id)->pluck('opportunity_id');
        $data = Opportunity::with(['investment'])
                ->whereIn('id', $opportunityIds)
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return OpportunityResource::collection($data);
    }

    public function index(Request $request){
        if(!empty($request->search)){
            $data = Investment::with(['user', 'opportunity'])
                    ->where('name', 'LIKE', '%', $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return InvestmentResource::collection($data);
        }
        $data = Investment::with(['user', 'opportunity'])
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return InvestmentResource::collection($data);
    }

    public function view($id) {
        $data = Investment::with(['opportunity', 'user'])->find($id);
        return new InvestmentResource($data);
    }

    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $investment = Investment::where('user_id', $user_id)
                    ->where('opportunity_id', $request->opportunity_id)
                    ->first();
        if(isset($investment)){
            return response()->json([
                'status' => 0,
                'message' => 'Sorry, The Investment is already submitted.'
            ]);
        }
        $data = new Investment();
        $data->user_id = $user_id;
        $data->opportunity_id = $request->opportunity_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->country = $request->country;
        $data->status = 'Processing';
        $data->company_name = $request->company_name;
        $data->profession = $request->profession;
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1, 
            'message' => 'Data saved successfully.',
            'opportunity' => $data->opportunity_id,
            'data' => new InvestmentResource($data),
        ]);
    }

    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $data = Investment::find($id);
        $data->opportunity_id = $request->opportunity_id;
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->country = $request->country;
        $data->company_name = $request->company_name;
        $data->profession = $request->profession;
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1, 
            'message' => 'Data saved successfully.',
            'opportunity' => $data->opportunity_id,
            'data' => new InvestmentResource($data),
        ]);
    }

    public function delete($id) {
        $data = Investment::find($id);
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Data deleted successfully.'
        ]);
    }
}
