<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Database\Eloquent\Builder;
use App\Pwb;

class PwbController extends Controller
{
    public function index(Request $request){
        $query = $this->getQuery();
        // return $this->applyFilter($query, $request);

        $result = $this->applyFilter($query, $request)
        ->paginate();
        return $result;
    }

    protected function getQuery(){
        return Pwb::select([
            'id',
            'model_header_id',
            'name'
        ]);
    }

    protected function applyFilter(Builder $query, Request $request){
        $params = $request->all(); //only( $this->allowedParameter );
        
        if (isset( $params['model_header_id'])) {
            $query = $query
            ->where('model_header_id', $params['model_header_id'] );
        }
                
        if (isset( $params['q'])) {
            $query = $query
            ->where('name', 'like', '%'. $params['q'] .'%' );
        }

        return $query;
    }


}
