<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberOrderResource;
use App\Http\Resources\UserResource;
use App\Models\Member;
use App\Models\MemberOrder;
use App\Models\MemberOrderInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




class MemberOrderController extends Controller
{

    public function statusUpdate(Request $request, $id) {
        $data = MemberOrder::find($id);
        if($request->status == 'Active' && $data->status == 'Processing') {
            $data->status = $request->status;
            $data->start_date = date('Y-m-d');
            $data->end_date =  date('Y-m-d', strtotime( $data->start_date . ' + ' . $data->duration . ' months'));
        }
        $data->updated_at = now();
        $data->save();
        /*  */
        return response()->json([
            'status' => 1,
            'message' => 'Data saved successfully.',
            'data' => new MemberOrderResource($data),
        ]);
    }


    public function checkout(Request $request) {
        $data = MemberOrder::find($request->member_order_id);
        $data->duration = $request->duration;
        $data->paid_amount = $request->paid_amount;
        $data->status = 'Processing';
        $data->updated_at = now();
        $data->save();
        /*  */
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->membership_id = $data->membership_id;
        $user->updated_at = now();
        $user->save();
        return response()->json([
            'status' => 1,
            'data' => new MemberOrderResource($data),
            'membership_id' => $data->membership_id,
            'message' => 'Data saved successfully.',
        ]);
    }


    public function indexByUser() {
        $user_id = Auth::user()->id;
        $data = MemberOrder::with(['membership', 'member_order_info'])
                ->where('user_id', $user_id)
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return MemberOrderResource::collection($data);
    }

    public function storeQrCode(Request $request) {
        $user_id = Auth::user()->id;
        $member = Member::where('user_id', $user_id)->first();
        if(isset($member)){
            $member->user_id = $user_id;
            $member->membership_id = $request->membership_id;
            $member->updated_at = now();
            $member->save();
            /*  */
            $order = new MemberOrder();
            $order->member_id = $member->id;
            $order->user_id = $user_id;
            $order->membership_id = $request->membership_id;
            $order->member_fee = $request->member_fee;
            $order->start_date = date('Y-m-d');
            $order->status = 'Active';
            $order->created_at = now();
            $order->updated_at = now();
            $order->save();
            /*  */
            $data = new MemberOrderInfo();
            $data->user_id = $user_id;
            $data->member_order_id = $order->id;
            $data->membership_id = $request->membership_id;
            $data->name = $request->name;
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->email = $request->email; // excluded in users table
            $data->country = $request->country;
            $data->website = $request->website;
            $data->who_join = $request->who_join;
            $data->company_name = $request->company_name;
            $data->profession = $request->profession;
            $data->created_at = now();
            $data->updated_at = now();
            $data->save();

            $user = User::find($user_id);
            $user->membership_id = $request->membership_id;
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->country = $request->country;
            $user->website = $request->website;
            $user->company_name = $request->company_name;
            $user->profession = $request->profession;
            $user->who_join = $request->who_join;
            $user->updated_at = now();
            $data->save();
            /*  */
            return response()->json([
                'status' => 1,
                'message' => 'Data saved successfully.',
                'data' => new MemberOrderResource($data),
                'member_order' => $order->id,
                'membership_id' => $user->membership_id,
            ]);

        } else{
            $member = new Member();
            $member->user_id = $user_id;
            $member->membership_id = $request->membership_id;
            $member->created_at = now();
            $member->updated_at = now();
            $member->save();
            /*  */
            $order = new MemberOrder();
            $order->member_id = $member->id;
            $order->user_id = $user_id;
            $order->membership_id = $request->membership_id;
            $order->member_fee = $request->member_fee;
            $order->status = 'Processing';
            $order->created_at = now();
            $order->updated_at = now();
            $order->save();
            /*  */
            $data = new MemberOrderInfo();
            $data->user_id = $user_id;
            $data->name = $request->name;
            $data->member_order_id = $order->id;
            $data->company_name = $request->company_name;
            $data->profession = $request->profession;
            $data->country = $request->country;
            $data->address = $request->address;
            $data->who_join = $request->who_join;
            $data->website = $request->website;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->created_at = now();
            $data->updated_at = now();
            $data->save();
            /* USER */
            $user = User::find($user_id);
            $user->membership_id = $request->membership_id;
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->country = $request->country;
            $user->website = $request->website;
            $user->company_name = $request->company_name;
            $user->profession = $request->profession;
            $user->who_join = $request->who_join;
            $user->updated_at = now();
            $data->save();
            /*  */
            return response()->json([
                'status' => 1,
                'message' => 'Data saved successfully.',
                'data' => new MemberOrderResource($data),
                'member_order' => $order->id,
                'membership_id' => $user->membership_id,
            ]);
        }
    }
    
    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $member = Member::where('user_id', $user_id)->first();
        if(isset($member)){
            $member->user_id = $user_id;
            $member->membership_id = $request->membership_id;
            $member->updated_at = now();
            $member->save();
            /*  */
            $order = new MemberOrder();
            $order->member_id = $member->id;
            $order->user_id = $user_id;
            $order->membership_id = $request->membership_id;
            $order->member_fee = $request->member_fee;
            $order->status = 'Processing';
            $order->created_at = now();
            $order->updated_at = now();
            $order->save();
            /*  */
            $data = new MemberOrderInfo();
            $data->user_id = $user_id;
            $data->member_order_id = $order->id;
            $data->membership_id = $request->membership_id;
            $data->name = $request->name;
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->email = $request->email;
            $data->country = $request->country;
            $data->company_name = $request->company_name;
            $data->profession = $request->profession;
            $data->created_at = now();
            $data->updated_at = now();
            $data->save();
            /*  */
            return response()->json([
                'status' => 1,
                'message' => 'Data saved successfully.',
                'data' => new MemberOrderResource($data),
                'member_order' => $order->id,
            ]);

        } else{
            $member = new Member();
            $member->user_id = $user_id;
            $member->membership_id = $request->membership_id;
            $member->created_at = now();
            $member->updated_at = now();
            $member->save();
            /*  */
            $order = new MemberOrder();
            $order->member_id = $member->id;
            $order->user_id = $user_id;
            $order->membership_id = $request->membership_id;
            $order->member_fee = $request->member_fee;
            $order->status = 'Processing';
            $order->created_at = now();
            $order->updated_at = now();
            $order->save();
            /*  */
            $data = new MemberOrderInfo();
            $data->user_id = $user_id;
            $data->name = $request->name;
            $data->member_order_id = $order->id;
            $data->company_name = $request->company_name;
            $data->profession = $request->profession;
            $data->country = $request->country;
            $data->address = $request->address;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->created_at = now();
            $data->updated_at = now();
            $data->save();
            /*  */
            return response()->json([
                'status' => 1,
                'message' => 'Data saved successfully.',
                'data' => new MemberOrderResource($data),
                'member_order' => $order->id,
            ]);
        }
    }


    public function index(Request $request) {
        if(!empty($request->search)) {
            $user = User::where('name', 'LIKE', '%' . $request->search . '%')->first();
            $data = MemberOrder::with(['user', 'member', 'membership', 'member_order_info'])
                    ->where('user_id', $user->id)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return MemberOrderResource::collection($data);
        }
        $data = MemberOrder::with(['user', 'member', 'membership', 'member_order_info'])
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return MemberOrderResource::collection($data);
    }


    public function view($id){
        $data = MemberOrder::with(['user', 'member', 'membership', 'member_order_info'])->find($id);
        return new MemberOrderResource($data);
    }


    public function delete($id) {
        $data = MemberOrder::find($id);
        MemberOrderInfo::where('member_order_id', $data->id)->delete();
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Data deleted successfully.'
        ]);
    }
}
