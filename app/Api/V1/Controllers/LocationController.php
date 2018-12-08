<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Database\Eloquent\Builder;
use App\Location;

class LocationController extends Controller
{
    protected $allowedParameter = [
        'model_header_id',
        'pwb_id',
        'modelname',
        'pwbname',
        'ref_no',
    ];

    public function index(Request $request){
        $query = $this->getQuery();
        // return $this->applyFilter($query, $request);

        $result = $this->applyFilter($query, $request)
        ->paginate();
        return $result;
    }

    protected function getQuery(){
        return Location::select([
            'locations.id',
            'model_headers.name as modelname',
            'pwbs.name as pwbname',
            'locations.ref_no'
        ])->join('pwbs', 'pwbs.id', '=', 'locations.pwb_id')
        ->join('model_headers', 'model_headers.id', '=', 'pwbs.model_header_id');
    }

    protected function applyFilter(Builder $query, Request $request){
        $params = $request->all(); //only( $this->allowedParameter );

        if (isset( $params['q'])) {
            $query = $query->where('locations.ref_no', 'like', "%{$params['q']}%" )
            ->orWhere('model_headers.name', 'like', $params['q'] .'%' )
            ->orWhere('pwbs.name', 'like', $params['q'] .'%' );
        }

        if(isset($params['model_header_id'])){
            $query = $query->where('model_headers.id', $params['model_header_id']);
        }

        if(isset($params['pwb_id'])){
            $pwbId = $params['pwb_id'];

            if(is_array( $pwbId)){
                $query = $query->whereIn('pwbs.id', $pwbId );
            }else{
                $query = $query->where('pwbs.id', $pwbId );
            }
        }
        return $query;
    }


}
