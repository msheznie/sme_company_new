<?php

namespace App\Repositories;

use App\Interfaces\FormOptionDetailsRepositoryInterface;
use App\Models\FormOptionDetails;
use App\Models\FormOptionMaster;
use http\Env\Response;

class FormOptionDetailsRepository implements FormOptionDetailsRepositoryInterface
{
    public function all()
    {
        return FormOptionMaster::with('formOptionMasterDetails')
            ->get();
    }

}
