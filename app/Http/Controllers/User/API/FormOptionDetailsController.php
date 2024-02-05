<?php

namespace App\Http\Controllers\User\API;

use App\Http\Controllers\Controller;
use App\Interfaces\FormOptionDetailsRepositoryInterface;
use Illuminate\Http\Request;

class FormOptionDetailsController extends Controller
{
    /**
     * @var FormOptionDetailsRepositoryInterface
     */
    private $formOptionDetails;

    public function __construct(FormOptionDetailsRepositoryInterface $formOptionDetailsRepository)
    {
        $this->formOptionDetails = $formOptionDetailsRepository;
    }


    public function getSelectOptionValues(Request $request)
    {
        return $this->formOptionDetails->all();
    }
}
