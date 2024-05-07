<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\CompanyRepository;
use Illuminate\Http\Request;

class CompanyAjaxController extends Controller
{
    protected $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->companyRepository->getDataTable($request);
    }

    public function show(Request $request)
    {
        return $this->companyRepository->show($request);
    }
    public function active(Request $request)
    {
        return $this->companyRepository->active($request);
    }
}
