<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBaseController as Controller;
use App\Sequence;

class SequenceController extends Controller
{
    public function showCopy($id) {
        $sequence = Sequence::select([
            'name',
            'modelname',
            'line_id',
            'pwbname',
            'process',
        ])
        ->find($id);

        return view('vendor.voyager.sequences.copy', ['sequence' => $sequence->toArray() ]);
    }
}
