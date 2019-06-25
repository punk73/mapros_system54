<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class Approved extends AbstractAction
{
    public function getTitle()
    {
        return 'My Action';
    }

    public function getIcon()
    {
        return 'voyager-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-success pull-right',
        ];
    }

    public function getDefaultRoute()
    {
        return null;
        // return route('/');
    }

    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'quality';
    }
}