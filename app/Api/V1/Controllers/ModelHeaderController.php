<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Database\Eloquent\Builder;
use App\ModelHeader;

class ModelHeaderController extends Controller
{
    public function index(Request $request){
        $query = $this->getQuery();
        // return $this->applyFilter($query, $request);

        $result = $this->applyFilter($query, $request)
        ->paginate();
        return $result;
    }

    protected function getQuery(){
        return ModelHeader::select([
            'id',
            'name'
        ]);
    }

    protected function applyFilter(Builder $query, Request $request){
        $params = $request->all(); //only( $this->allowedParameter );
        
        /*foreach ($params as $key => $param ) {
            if (isset($params[$key]) && $param != '' && ($key != 'q') ) {
                $query = $query->where($key, 'like', $param .'%' );
            }
        }*/

        if (isset( $params['q'])) {
            $query = $query
            ->where('model_headers.name', 'like', '%'. $params['q'] .'%' );
        }

        return $query;
    }


}
