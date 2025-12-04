<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\EventCart;
use App\Models\Membership;
use App\Models\QrCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public $upload_location = 'assets/img/user/';

    public function check(){
        if(Auth::check()) {
            $user_id = Auth::user()->id;
            return response()->json([
                'status' => 1,
                'user_id' => $user_id,
            ]);
        }
        return response()->json([
            'status' => 0,
        ]);
    }

    public function qrcodeLogin(Request $request) {
        $qrcode = QrCode::where('code', $request->code)->first();
        if(!isset($qrcode)){
            return response()->json([
                'status' => 0,
                'message' => 'QR Code is not found.'
            ]);
        }
        if($qrcode->status != 'Used'){
            return response()->json([
                'status' => 2,
                'message' => 'QR Code is not assigned to a user.'
            ]);
        }
        if(isset($qrcode)){
            $data = User::find($qrcode->user_id);
            $membership_id = null;
            if(isset($data->membership_id)) {
                $membership_id = $data->membership_id;
            }
            /*  */
            return response()->json([
                'status' => 1,
                'message' => 'Login Successful.',
                'auth_token' => $data->createToken($data->email)->plainTextToken,
                'role_level' => $data->role_level,
                'membership_id' => $membership_id,
            ]);
        }
    }

    public function qrcodeRegister(Request $request) {
        //Check QR Code
        $qrcode = QrCode::where('code', $request->code)->first();
        if(!isset($qrcode)) {
            return response()->json([
                'status' => 0,
                'message' => 'QR Code does not exist, please try a different one.',
            ]);
        }
        /* CHECK STATUS */
        if($qrcode->status != 'Available') {
            return response()->json([
                'status' => 2,
                'message' => 'QR Code is already used, please try a another one.',
            ]);
        }
        //Check Email
        $email = User::where('email', $request->email)->first();
        if(isset($email)){
            return response()->json([
                'message' => 'Email is aleady taken, please try another one.',
                'status' => 3,
            ]);
        }
        $membership = Membership::first();
        /* USER */
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->membership_id = $membership->id;
        $data->role_level = 4;
        $data->password = Hash::make($request->password);
        $data->code = $request->password;
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        /* QR CODE UPDATE STATUS */
        $qrcode->status = 'Used';
        $qrcode->user_id = $data->id;
        $qrcode->updated_at = now();
        $qrcode->save();
        /* RETURN */
        return response()->json([
            'status' => 1,
            'message' => 'Created Successfully.',
            'data' => new UserResource($data),
            'auth_token' => $data->createToken($data->email)->plainTextToken,
            'role_level' => $data->role_level,
        ]);

    }
    
    public function register(Request $request){
        $email = User::where('email', $request->email)->first();
        if(isset($email)){
            return response()->json([
                'message' => 'Email is aleady taken, please try another one.',
                'status' => 0,
            ]);
        }
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->code = $request->password;
        $data->password = Hash::make($request->password);
        $data->role_level = 4;
        $data->updated_at = now();
        $data->created_at = now();
        $data->save();
        /*  */
        $user = User::with(['membership'])->where('email', $request->email)->first();
        /* Dealing with Membership. */
        $membership_id = null;
        if($user->membership()->exists()){
            $membership_id = $user->membership->id;
        }
        return response()->json([
            'status' => 1,
            'message' => 'Created Successfully.',
            'data' => new UserResource($data),
            'auth_token' => $user->createToken($user->email)->plainTextToken,
            'role_level' => $user->role_level,
            'membership_id' => $membership_id,
        ]);
    }

    public function login(Request $request){
        $user = User::with(['membership'])->where('email', $request->email)->first();
        /* Check Email... */
        if(!isset($user)){
            return response()->json([
                'message' => 'Email was not found.',
                'status' => 0,
            ]);
        }
        /* Check Password... */
        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'The password is incorrect.',
                'status' => 2,
            ]);
        }
        /* Dealing with Membership. */
        $membership_id = null;
        if(isset($user->membership_id)){
            $membership_id = $user->membership_id;
        }
        /*  */
        return response()->json([
            'status' => 1,
            'message' => 'Login Successful.',
            'auth_token' => $user->createToken($user->email)->plainTextToken,
            'role_level' => $user->role_level,
            'membership_id' => $membership_id,
        ]);
    }

    public function password(Request $request){
        $user_id = Auth::user()->id;
        $data = User::find($user_id);
        $data->code = $request->password;
        $data->password = Hash::make($request->password);
        $data->save();
        return response()->json([
            'status' => 1,
            'message' => 'Password updated successfully.',
            'data' => new UserResource($data),
        ]);
    }

    public function view(){
        $user_id = Auth::user()->id;
        $data = User::with(['role', 'membership', 'qrcode'])->find($user_id);
        return response()->json([
            'data' => new UserResource($data),
        ]);
    }

    public function update(Request $request){
        $user_id = Auth::user()->id;
        $email = User::where('id', '!=', $user_id)->where('email', $request->email)->first();
        if(isset($email)){
            return response()->json([
                'status' => 0,
                'message' => 'Email already exists, try another one.',
            ]);
        }
        $data = User::find($user_id);
        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->country = $request->country;
        $data->gender = $request->gender;
        $data->country = $request->country;
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
            'message' => 'Profile updated successfully.',
            'data' => new UserResource($data),
        ]);
    }
    
    public function emailUpdate(Request $request){
        $user_id = Auth::user()->id;
        $email = User::where('id', '!=', $user_id)->where('email', $request->email)->first();
        if(isset($email)){
            return response()->json([
                'status' => 0,
                'message' => 'Email already exists, try another one.',
            ]);
        }
        $data = User::find($user_id);
        $data->email = $request->email;
        $data->save();
        return response()->json([
            'status' => 1,
            'message' => 'Email updated successfully.',
        ]);
    }

    public function logout(Request $request){
        if(!empty($request->cart_token)) {
            $event_cart = EventCart::where('cart_token', $request->cart_token)->get();
            if(isset($event_cart)) {
                EventCart::where('cart_token', $request->cart_token)->delete();
            }
        }
        /*  */
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Logged out succesfully.',
        ]);
    }
    
}
