<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\EmployeeRepository;
use Illuminate\Http\Request;

class EmployeeAjaxController extends Controller
{
    protected $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->employeeRepository->getDataTable($request);
    }

    public function show(Request $request)
    {
        return $this->employeeRepository->show($request);
    }
    public function active(Request $request)
    {
        return $this->employeeRepository->active($request);
    }
}
