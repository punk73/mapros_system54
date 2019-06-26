<?php

namespace App\Http\Controllers\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadImagesDeleted;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;
use App\Quality;
use App\Board;

class QualityController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    //...
    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];
        $searchable = $dataType->server_side ? array_keys(SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray()) : '';
        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', null);
        $orderColumn = [];
        if ($orderBy) {
            $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + 1;
            $orderColumn = [[$index, 'desc']];
            if (!$sortOrder && isset($dataType->order_direction)) {
                $sortOrder = $dataType->order_direction;
                $orderColumn = [[$index, $dataType->order_direction]];
            } else {
                $orderColumn = [[$index, 'desc']];
            }
        }

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $query = $model::select([
                'ID_QUALITY'
                ,'MODEL'
                ,'BOARD'
                ,'PCB_ID_NEW'
                ,'PCB_ID_OLD'
                ,'GUIDMASTER'
                ,'APPROVED'
            ])->where('GUIDMASTER', '!=', null )
                ->where('PCB_ID_OLD', '!=', "-")
                ->where(function($query){
                    return $query->where('APPROVED', '=', NULL )
                      ->orWhere('APPROVED', 0);
                })
                ->orderBy('ID_QUALITY','desc');

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';
                $query->where($search->key, $search_filter, $search_value);
            }

            if ($orderBy && in_array($orderBy, $dataType->fields())) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                $dataTypeContent = call_user_func([
                    $query->orderBy($orderBy, $querySortOrder),
                    $getter,
                ]);
            } elseif ($model->timestamps) {
                $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        // Check if BREAD is Translatable
        if (($isModelTranslatable = is_bread_translatable($model))) {
            $dataTypeContent->load('translations');
        }

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = isset($dataType->default_search_key) ? $dataType->default_search_key : null;

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return Voyager::view($view, compact(
            'dataType',
            'dataTypeContent',
            'isModelTranslatable',
            'search',
            'orderBy',
            'orderColumn',
            'sortOrder',
            'searchable',
            'isServerSide',
            'defaultSearchKey'
        ));
    }

    public function approve($id, Request $request) {

        $quality = Quality::select([
            'ID_QUALITY'
            ,'MODEL'
            ,'BOARD'
            ,'PCB_ID_NEW'
            ,'PCB_ID_OLD'
            ,'GUIDMASTER'
            ,'APPROVED'
        ])->where('GUIDMASTER', '!=', null )
            ->where('PCB_ID_OLD', '!=', "-")
            ->where(function($query){
                return $query->where('APPROVED', '=', NULL )
                  ->orWhere('APPROVED', 0);
            })
            ->orderBy('ID_QUALITY','desc')
            ->find($id);
        
        if($quality) {
            
            // swap the master guid;
            // pib_id_new->guid_master = pcb_id_old->guid_master
            $pcb_id_new = trim($quality->PCB_ID_NEW);
            $pcb_id_old = trim($quality->PCB_ID_OLD);
            $guidMaster = trim($quality->GUIDMASTER);
            
            $data = $this->swapGuid($pcb_id_new, $pcb_id_old, $guidMaster );
            
            if(!$data) {
                return redirect()
                  ->back()
                  ->with(['message' => "DATA BOARD BARU TIDAK DITEMUKAN. {$pcb_id_new}", 'alert-type' => 'error']);
            }

            $quality->APPROVED = 1; //true;
            $quality->save();

            return redirect()->back()->with([
                'message' => "DATA BERHASIL DI APPROVE", 'alert-type' => 'success'
            ]);
        }

        return redirect()->back();
    }

    public function getDummyParent($boardId){
		$tmp = str_split($boardId);

		if(strlen($boardId) == 16 ){
			$tmp[7] = 0;
			$tmp[8] = 0;
		}else if(strlen($boardId) == 24 ){
			$tmp[12] = 0;
			$tmp[13] = 0;
		}

		return implode('', $tmp );
    }
    
    public function ignoreSideQuery($query, $boardId ) {
        $tmp = $this->getDummyParent($boardId);
				
        if(strlen($boardId) == 16 ){
            $sideIndex = 6;
        }else if (strlen($boardId) == 24 ){
            $sideIndex = 14;
        }else{
            // default side index yg sekarang adalah 14; karena ini yg berlaku;
            $sideIndex = 14;
        }
        
        $parentA = $tmp;
        $parentA[$sideIndex] = 'A';

        $parentB = $tmp;
        $parentB[$sideIndex] = 'B';

        $a = $boardId;
        $a[$sideIndex] = 'A';
        $b = $boardId;
        $b[$sideIndex] = 'B';

        $query->where('board_id', $a )
            ->orWhere('board_id', $b )
            ->orWhere('board_id', $parentA )
            ->orWhere('board_id', $parentB );
    }

    public function swapGuid($pcbIdNew, $pcbIdOld, $guidMaster ) {
        $new = Board::where( function($query) use ($pcbIdNew) { $this->ignoreSideQuery($query, $pcbIdNew ); } )
        ->where('guid_master', '=', null )  
        ->orderBy('id', 'desc')
        ->update(['guid_master' => $guidMaster ]);

        if( $new == 0 ) {
            // jika ga ada yang ter update, gausah lanjut. 
            // nanti board baru ga ada, board lama keupdate
            return false;
        }

        $old = Board::where( function($query) use ($pcbIdOld) { $this->ignoreSideQuery($query, $pcbIdOld ); } )
        ->orderBy('id', 'desc')
        ->update(['guid_master' => $guidMaster . '_old' ]);;

        return true;
        /* return [
            'new' => $new, //jumlah data terupdate.
            'old' => $old,
            'guid' => $guidMaster
        ]; */
        
    }

}