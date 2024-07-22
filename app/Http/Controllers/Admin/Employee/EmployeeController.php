<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\UpdateRequest;
use App\Models\Employee\Employee;
use App\Repositories\Admin\EmployeeRepository;

class EmployeeController extends Controller
{
    protected $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function index()
    {
        return view('admin.employees.index');
    }

    public function update(UpdateRequest $request, Employee $employee)
    {
        $this->employeeRepository->update($request, $employee);
        return redirect()->route('admin.employees.index');
    }
}
