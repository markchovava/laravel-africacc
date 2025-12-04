<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public $upload_location = 'assets/img/news/';
    

    public function indexByNum(Request $request) {
        $data = News::orderBy('priority', 'asc')->paginate($request->num);
        return NewsResource::collection($data);
    }

    public function index(Request $request) {
        if(!empty($request->search)){
            $data = News::with(['user'])
                    ->where('title', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return NewsResource::collection($data);
        }
        $data = News::with(['user'])
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return NewsResource::collection($data);
    }

    public function view($id) {
        $data = News::with(['user'])->find($id);
        return new NewsResource($data);
    }

    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new News();
        $data->user_id = $user_id;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->date = $request->date;
        $data->location = $request->location;
        $data->priority = $request->priority;
        $data->slug = $request->slug;
        $data->status = $request->status;
        if( !empty($request->hasFile('image')) ) {
            $image = $request->file('image');
            $image_extension = strtolower($image->getClientOriginalExtension());
            $image_name = 'news_' . date('Yi') . rand(0, 1000) . '.' . $image_extension;
            $image->move($this->upload_location, $image_name);
            $data->image = $this->upload_location . $image_name;                        
        }
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'message' => 'Data successfully saved.',
            'data' => new NewsResource($data),
        ]);
    }

    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $data = News::find($id);
        $data->user_id = $user_id;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->date = $request->date;
        $data->location = $request->location;
        $data->priority = $request->priority;
        $data->slug = $request->slug;
        $data->status = $request->status;
        if( !empty($request->hasFile('image')) ){
            $image = $request->file('image');
            $image_extension = strtolower($image->getClientOriginalExtension());
            $image_name = 'news_' . date('Yi') . rand(0, 1000) . '.' . $image_extension;
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
            'message' => 'Data successfully saved.',
            'data' => new NewsResource($data),
        ]);
    }

    public function delete($id){
        $data = News::find($id);
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Data deleted successfully.',
        ]);
    }

}
