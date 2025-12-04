<?php

namespace App\Http\Controllers;

use App\Http\Resources\OpportunityResource;
use App\Models\Country;
use App\Models\Opportunity;
use App\Models\OpportunitySector;
use App\Models\Sector;
use Illuminate\Http\Request;

class CountryOpportunityController extends Controller
{
    public function indexOpportunityByCountry(Request $request){
        $country = Country::where('slug', $request->slug)->first();
        if(!empty($request->search)){
            $data = Opportunity::with(['sectors', 'country', 'opportunity_images'])
                    ->where('country_id', $country->id)
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return OpportunityResource::collection($data);
        }
        $data = Opportunity::with(['sectors', 'country', 'opportunity_images'])
                ->where('country_id', $country->id)
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return OpportunityResource::collection($data);
    }

    public function indexOpportunityByCountrySector(Request $request){
        $country = Country::where('slug', $request->country_slug)->first();
        $opportunityIds = OpportunitySector::where('sector_id', $request->sector_id)
                    ->pluck('opportunity_id');
        if(!empty($request->search)){
            $data = Opportunity::with(['country', 'opportunity_images', 'sectors'])
                    ->whereIn('id', $opportunityIds)
                    ->where('country_id', $country->id)
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            return OpportunityResource::collection($data);
        }
        $data = Opportunity::with(['country', 'opportunity_images', 'sectors'])
                ->whereIn('id', $opportunityIds)
                ->where('country_id', $country->id)
                ->orderBy('updated_at', 'desc')
                ->paginate(12);
        return OpportunityResource::collection($data);
    }
}
