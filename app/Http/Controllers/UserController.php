<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public $upload_location = 'assets/img/user/';

    public function generateRandomText($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);
        return substr($shuffled, 0, $length);
    }

    public function searchEmail(Request $request) {
        $data = User::where('email', 'LIKE', '%' . $request->search . '%')->paginate(12);
        return UserResource::collection($data);
    }
    
    public function index(Request $request){
        $user_id = Auth::user()->id;
        if(!empty($request->search)) {
            $data = User::with(['role', 'qrcode'])->where('id', '!=', $user_id)
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return  UserResource::collection($data);
        } else{
            $data = User::with(['role', 'qrcode'])->where('id', '!=', $user_id)
                    ->orderBy('name', 'asc')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return UserResource::collection($data);
        }
    }

    public function store(Request $request){
        $code = $this->generateRandomText();
        $email = User::where('email', $request->email)->first();
        if(isset($email)){
            return response()->json([
                'status' => 0,
                'message' => 'Email already exists, try another one.',
            ]);
        }
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->country = $request->country;
        $data->gender = $request->gender;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->role_level = $request->role_level;
        $data->code = $code;
        $data->password = Hash::make($code);
        if( !empty($request->hasFile('image')) ) {
            $image = $request->file('image');
            $image_extension = strtolower($image->getClientOriginalExtension());
            $image_name = 'user_' . date('Yi') . rand(0, 1000) . '.' . $image_extension;
            $image->move($this->upload_location, $image_name);
            $data->image = $this->upload_location . $image_name;                        
        }  
        $data->created_at = now();                    
        $data->updated_at = now();                    
        $data->save();
        return response()->json([
            'status' => 1,
            'message' => 'Saved successfully.',
            'data' => new UserResource($data),
        ]);
    }

    public function view($id){
        $data = User::with(['role', 'qrcode'])->find($id);
        return  new UserResource($data);
    }

    public function update(Request $request, $id){
        $email = User::where('id', '!=', $id)
                ->where('email', $request->email)->first();
        if(isset($email)){
            return response()->json([
                'status' => 0,
                'message' => 'Email already exists, try another one.',
            ]);
        }
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->country = $request->country;
        $data->gender = $request->gender;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->role_level = $request->role_level;  
        if( $request->hasFile('image') ){
            $image = $request->file('image');
            $image_extension = strtolower($image->getClientOriginalExtension());
            $image_name = 'user_' . date('Yi') . rand(0, 1000) . '.' . $image_extension;
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
            'message' => 'Saved successfully.',
            'data' => new UserResource($data),
        ]);
    }

    public function delete($id){
        $data = User::find($id);
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
