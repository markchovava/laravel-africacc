<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function viewBySlug(Request $request){
        $data = Role::with(['user'])
                ->where('slug', $request->slug)->first();
        return new RoleResource($data);
    }

    public function indexAll(){
        $data = Role::orderBy('level', 'asc')->get();
        return RoleResource::collection($data);
    }

    public function index(Request $request) {
        if($request->search) {
            $data = Role::with(['user'])
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('level', 'asc')
                    ->paginate(12);
            return RoleResource::collection($data);
        }
        $data = Role::with(['user'])
                ->orderBy('level', 'asc')
                ->paginate(12);
        return RoleResource::collection($data);
    }

    public function view($id) {
        $data = Role::with(['user'])->find($id);
        return new RoleResource($data);
    }

    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new Role();
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->slug = $request->slug;
        $data->level = $request->level;  
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new RoleResource($data),
            'message' => 'Data successfully saved.',
        ]); 
    }

    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $data = Role::find($id);
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->slug = $request->slug;
        $data->level = $request->level;
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new RoleResource($data),
            'message' => 'Data successfully saved.',
        ]); 
    }

    public function delete($id) {
        $data = Role::find($id);
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Deleted successfully.'
        ]);
    }


}
