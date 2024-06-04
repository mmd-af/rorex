<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Company\UpdateRequest;
use App\Models\Company\Company;
use App\Repositories\Admin\CompanyRepository;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        return view('admin.companies.index');
    }

    public function update(UpdateRequest $request, Company $company)
    {
        $this->companyRepository->update($request, $company);
        return redirect()->route('admin.companies.index');
    }
}
