<?php

namespace App\Http\Controllers;

use App\Http\Resources\MembershipResource;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function viewBySlug(Request $request){
        $data = Membership::with(['user'])
                ->where('slug', $request->slug)->first();
        return new MembershipResource($data);
    }

    public function indexByNum(Request $request) {
        $data = Membership::with(['user'])
                ->orderBy('level', 'asc')
                ->paginate($request->num);
        return MembershipResource::collection($data);
    }

    public function indexAll(){
        $data = Membership::orderBy('level', 'asc')->get();
        return MembershipResource::collection($data);
    }

    public function index(Request $request) {
        if($request->search) {
            $data = Membership::with(['user'])
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('level', 'asc')
                    ->paginate(12);
            return MembershipResource::collection($data);
        }
        $data = Membership::with(['user'])
                ->orderBy('level', 'asc')
                ->paginate(12);
        return MembershipResource::collection($data);
    }


    public function view($id) {
        $data = Membership::with(['user'])->find($id);
        return new MembershipResource($data);
    }


    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new Membership();
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->description = $request->description;  
        $data->slug = $request->slug;
        $data->fee = $request->fee;
        $data->level = $request->level;
        $data->priority = $request->priority;  
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new MembershipResource($data),
            'message' => 'Data successfully saved.',
        ]); 
    }


    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $data = Membership::find($id);
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->description = $request->description;  
        $data->slug = $request->slug;
        $data->fee = $request->fee;
        $data->level = $request->level;
        $data->priority = $request->priority; 
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new MembershipResource($data),
            'message' => 'Data successfully saved.',
        ]); 
    }


    public function delete($id) {
        $data = Membership::find($id);
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Deleted successfully.'
        ]);
    }
}
