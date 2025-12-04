<?php

namespace App\Http\Controllers;

use App\Models\OpportunityImage;
use Illuminate\Http\Request;

class OpportunityImageController extends Controller
{


    public function delete($id){
        $data = OpportunityImage::find($id);
        if(isset($data->image)){
            if(file_exists( public_path($data->image) )){
                unlink($data->image);
            }
        }
        $data->delete();

        return response()->json([
            'status', 1,
            'message' => 'Deleted successully.'
        ]);
    }
    
}
