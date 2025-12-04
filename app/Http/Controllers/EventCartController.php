<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventCartResource;
use App\Models\EventCart;
use Illuminate\Http\Request;

class EventCartController extends Controller
{
    
    public function generateRandomText($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);
        return substr($shuffled, 0, $length);
    }

    public function viewByToken(Request $request) {
        if(!empty($request->cart_token)) {
            $data = EventCart::where('cart_token', $request->cart_token)->first();
            if(!isset($data)){
                return response()->json([
                    'status' => 0,
                    'message' => 'No Event selected.',
                ]);
            }
            return new EventCartResource($data);
        }
        return response()->json([
            'status' => 0,
            'message' => 'No Event Selected.',
        ]);
    }

    public function store(Request $request){
        if(!empty($request->cart_token)) {
            $data = EventCart::where('cart_token', $request->cart_token)->first();
            if(!isset($data)){
                $data = new EventCart();
                $data->cart_token = date('Ym') . $this->generateRandomText(5);
                $data->event_id = $request->event_id;
                $data->event_total = $request->event_total;
                $data->joining_fee = $request->joining_fee;
                $data->number_of_people = $request->number_of_people;
                $data->created_at = now();
                $data->updated_at = now();
                $data->save();
                return response()->json([
                    'status' => 1,
                    'message' => 'Data saved successfully.',
                    'data' => new EventCartResource($data),
                    'cart_token' => $data->cart_token,
                ]);
            }
            $data->event_id = $request->event_id;
            $data->event_total = $request->event_total;
            $data->joining_fee = $request->joining_fee;
            $data->number_of_people = $request->number_of_people;
            $data->updated_at = now();
            $data->save();
            return response()->json([
                'status' => 1,
                'message' => 'Data saved successfully.',
                'data' => new EventCartResource($data),
                'cart_token' => $data->cart_token,
            ]);
        }
        $data = new EventCart();
        $data->cart_token = date('Ym') . $this->generateRandomText(5);
        $data->event_id = $request->event_id;
        $data->event_total = $request->event_total;
        $data->joining_fee = $request->joining_fee;
        $data->number_of_people = $request->number_of_people;
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        /*  */
        return response()->json([
            'status' => 1,
            'message' => 'Data saved successfully.',
            'data' => new EventCartResource($data),
            'cart_token' => $data->cart_token,
        ]);
    }

    public function index() {
        $data = EventCart::with(['event'])->orderBy('updated_at', 'desc')->paginate(12);
        return EventCartResource::collection($data);
    }

    public function delete($id) {
        $data = EventCart::find($id);
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Data successfully deleted.',
        ]);
    }

}
