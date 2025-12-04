<?php

namespace App\Http\Controllers;

use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    public function indexByNum(Request $request) {
        $data = Testimonial::orderBy('priority', 'asc')->paginate($request->num);
        return TestimonialResource::collection($data);
    }

    public function index(Request $request) {
        if(!empty($request->search)) {
            $data = Testimonial::with(['user'])
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return TestimonialResource::collection($data);
        }
        $data = Testimonial::with(['user'])
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return TestimonialResource::collection($data);
    }

    public function view($id) {
        $data = Testimonial::with(['user'])->find($id);
        return new TestimonialResource($data);
    }

    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new Testimonial();
        $data->user_id = $user_id;
        $data->priority = $request->priority;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->description = $request->description;
        $data->updated_at = now();
        $data->created_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new TestimonialResource($data),
            'message' => 'Data successfully saved.',
        ]);
    }

    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $data = Testimonial::find($id);
        $data->user_id = $user_id;
        $data->priority = $request->priority;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->description = $request->description;
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'data' => new TestimonialResource($data),
            'message' => 'Data successfully saved.',
        ]);
    }

    public function delete($id) {
        $data = Testimonial::find($id);
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
