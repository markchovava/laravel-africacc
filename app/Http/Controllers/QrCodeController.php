<?php

namespace App\Http\Controllers;

use App\Http\Resources\QrCodeResource;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


class QrCodeController extends Controller
{
    public $upload_location = 'assets/img/qrcode/';
    public function generateRandomText($length = 9) {
        $date = date('Ymdhis');
        $characters = $date . '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffled = str_shuffle($characters);
        return substr($shuffled, 0, $length);
    }

    public function generateQrCode($data) {
        $builder = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(400)
            ->margin(20)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->labelText($data)
            ->labelFont(new NotoSans(20))
            ->labelAlignment(LabelAlignment::Center)
            ->validateResult(false);
        $result = $builder->build();
        // Save it to a file
        $result->saveToFile(public_path($this->upload_location . $data . '.png' ));  
    }


    /* public function generateQrCode($data) {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 20,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            labelText: $data,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        ); 
        $result = $builder->build();
        // Save it to a file
        $result->saveToFile(public_path($this->upload_location . $data . '.png' ));   
    } */


    public function assignUser(Request $request){
        $data = QrCode::find($request->qrcode_id);
        $data->user_id = $request->user_id;
        $data->status = 'Used';
        $data->updated_at = now();
        $data->save();
        return response()->json([
            'status' => 1, 
            'message' => 'User QR Code assigned successfully.'
        ]);
    }

    public function indexByStatus(Request $request){
        if(!empty($request->status)){
            $data = QrCode::with(['user'])
                    ->where('status', $request->status)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return QrCodeResource::collection($data);
        }
        $data = QrCode::with(['user'])
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return QrCodeResource::collection($data);
    }
    
    public function indexByNumStatus(Request $request){
        $data = QrCode::with(['user'])
                ->orderBy('updated_at', 'desc')
                ->where('status', $request->status)
                ->paginate($request->num);
        return QrCodeResource::collection($data);
    }

    public function indexByNum(Request $request){
        $data = QrCode::with(['user'])
                ->orderBy('updated_at', 'desc')
                ->paginate($request->num);
        return QrCodeResource::collection($data);
    }

    public function index(){
        $data = QrCode::with(['user'])->orderBy('updated_at', 'desc')->paginate(12);
        return QrCodeResource::collection($data);
    }

    public function search(Request $request){
        $data = QrCode::with(['user'])
                ->where('code', 'LIKE', '%' . $request->search . '%')
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return QrCodeResource::collection($data);
    }

    public function storeByNum(Request $request) {
        Log::info('$request->quantity');
        Log::info($request->quantity);
        for($i = 0; $i < (int)$request->quantity; $i++) {
            $data = new QrCode();
            $data->code = date('Yhs') . rand(0, 10000) . $this->generateRandomText(8);
            $data->status = 'Available';
            $data->created_at = now();
            $data->updated_at = now();
            $data->save();
            $this->generateQrCode($data->code);
        }
        return response()->json([
            'status' => 1,
            'message' => 'Data saved successfully.'
        ]);
    }

    public function store(Request $request) {
        $user_id = Auth::user()->id;
        $data = new QrCode();
        $data->code = date('Yhs') . rand(0, 10000) . $this->generateRandomText(8);
        $data->user_id = $user_id;
        $data->status = 'Available';
        $data->created_at = now();
        $data->updated_at = now();
        $data->save();
        $this->generateQrCode($data->code);
        return response()->json([
            'status' => 1,
            'message' => 'Data saved successfully.',
            'data' => new QrCodeResource($data),
        ]);
    }

    public function view($id){
        $data = QrCode::with(['user'])->find($id);
        return new QrCodeResource($data);
    }

    public function delete($id) {
        $data = QrCode::find($id);
        if(isset($data->code)){
            if(file_exists( public_path($this->upload_location . $data->code . '.png') )){
                $image = $this->upload_location . $data->code . '.png';
                unlink($image);
            }
        }
        $data->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Data deleted successfully.',
            'data' => new QrCodeResource($data),
        ]);

    }


}
