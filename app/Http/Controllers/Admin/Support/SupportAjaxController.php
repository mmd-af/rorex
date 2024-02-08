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
    public function getArchiveDataTable(Request $request)
    {
        return $this->supportRepository->getArchiveDataTable($request);
    }

    public function show(Request $request)
    {
        return $this->supportRepository->show($request);
    }

    public function archivedShow(Request $request)
    {
        return $this->supportRepository->archivedShow($request);
    }
}
