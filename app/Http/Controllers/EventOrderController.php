<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventOrderResource;
use App\Models\Event;
use App\Models\EventCart;
use App\Models\EventOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventOrderController extends Controller
{
    
    public function indexByUser(Request $request) {
        $user_id = Auth::user()->id;
        if(!empty($request->search)) {
            $eventIds = Event::where('name', 'LIKE', '%' . $request->search . '%')->pluck('id');
            $data = EventOrder::with(['event', 'user'])
                    ->whereIn('event_id', $eventIds)
                    ->where('user_id', $user_id)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return EventOrderResource::collection($data);
        }
        $data = EventOrder::with(['event', 'user'])
                ->where('user_id', $user_id)
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return EventOrderResource::collection($data);
    }

    public function statusUpdate(Request $request) {
        $data = EventOrder::find($request->event_order_id);
        $data->status = $request->status;
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'message' => 'Data updated successfully.',
        ]);

    }

    public function update(Request $request, $id) {
        $user_id = Auth::user()->id;
        $data = EventOrder::find($id);
        $data->user_id = $user_id;
        $data->event_id = $request->event_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->company_name = $request->company_name;
        $data->country = $request->country;
        $data->is_agree = $request->is_agree;
        $data->payment_method = $request->payment_method;
        $data->phone = $request->phone;
        $data->profession = $request->profession;
        $data->joining_fee = $request->joining_fee;
        $data->number_of_people = $request->number_of_people;
        $data->event_total = $request->event_total;
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1,
            'message' => 'Data saved successfully.',
            'data' => new EventOrderResource($data),
        ]);
    }

    public function index(Request $request) {
        if(!empty($request->search)) {
            $eventIds = Event::where('name', 'LIKE', '%' . $request->search . '%')->pluck('id');
            $data = EventOrder::with(['event', 'user'])
                    ->whereIn('event_id', $eventIds)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return EventOrderResource::collection($data);
        }
        $data = EventOrder::with(['event', 'user'])
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return EventOrderResource::collection($data);
    }
   
    public function view($id) {
        $data = EventOrder::with(['event', 'user'])->find($id);
        return new EventOrderResource($data);
    }

    public function delete($id) {
        $data = EventOrder::find($id);
        if(!isset($data)) {
            return response()->json([
                'status' => 0,
                'message' => 'Data not found.',
            ]);
        }
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Data successfully deleted..',
        ]);
    }

    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new EventOrder();
        $data->user_id = $user_id;
        $data->event_id = $request->event_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->company_name = $request->company_name;
        $data->country = $request->country;
        $data->status = 'Processing';
        $data->is_agree = $request->is_agree;
        $data->payment_method = $request->payment_method;
        $data->phone = $request->phone;
        $data->profession = $request->profession;
        $data->joining_fee = $request->joining_fee;
        $data->number_of_people = $request->number_of_people;
        $data->event_total = $request->event_total;
        $data->updated_at = now();
        $data->created_at = now();
        $data->save();
        EventCart::find($request->event_cart_id)->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Data saved successfully.',
            'data' => new EventOrderResource($data),
        ]);
    }

}
