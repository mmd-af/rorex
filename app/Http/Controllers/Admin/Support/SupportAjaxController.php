<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\SupportRepository;
use Illuminate\Http\Request;

class SupportAjaxController extends Controller
{
    protected $supportRepository;

    public function __construct(SupportRepository $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->supportRepository->getDataTable($request);
    }

    public function storeReaded(Request $request)
    {
        return $this->supportRepository->storeReaded($request);
    }
}
