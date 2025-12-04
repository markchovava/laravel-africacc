<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    
    public function indexByNum(Request $request) {
        $data = Event::orderBy('priority', 'asc')->paginate($request->num);
        return EventResource::collection($data);
    }

    public function index(Request $request) {
        if(!empty($request->search)){
            $data = Event::with(['user'])
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return EventResource::collection($data);
        }
        $data = Event::with(['user'])
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return EventResource::collection($data);
    }


    public function view($id) {
        $data = Event::with(['user'])->find($id);
        return new EventResource($data);
    }


    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new Event();
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->date = $request->date;
        $data->status = $request->status;
        $data->joining_fee = $request->joining_fee;
        $data->location = $request->location;
        $data->duration = $request->duration;
        $data->priority = $request->priority;
        $data->slug = $request->slug;
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'message' => 'Data successfully saved.',
            'data' => new EventResource($data),
        ]);
    }


    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $data = Event::find($id);
        $data->user_id = $user_id;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->date = $request->date;
        $data->status = $request->status;
        $data->joining_fee = $request->joining_fee;
        $data->location = $request->location;
        $data->duration = $request->duration;
        $data->priority = $request->priority;
        $data->slug = $request->slug;
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'message' => 'Data successfully saved.',
            'data' => new EventResource($data),
        ]);
    }


    public function delete($id){
        $data = Event::find($id);
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Data deleted successfully.',
        ]);
    }


}
