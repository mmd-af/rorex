<?php

namespace App\Repositories\Admin;

use App\Events\CompanyActivedEvent;
use App\Models\Company\Company;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class CompanyRepository extends BaseRepository
{
    public function __construct(Company $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $data = $this->query()
            ->select([
                'id',
                'user_id',
                'company_name',
                'activity_domain',
                'city'
            ])
            ->with('users')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('email', function ($row) {
                    return $row->users->email;
                })
                ->addColumn('is_active', function ($row) {
                    return '
                    <div class="form-switch">
                    <input onclick="handleActive(event, ' . $row->users->id . ')" class="form-check-input" type="checkbox" role="switch" id="signed_by" name="signed_by" value="' . $row->users->is_active . '" ' . ($row->users->is_active ? 'checked' : '') . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<button onclick="show(' . $row->id . ')" type="button"
                                    class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#show">
                               <i class="fa-solid fa-eye"></i>
                            </button>';
                })
                ->rawColumns(['email', 'is_active', 'action'])
                ->make(true);
        }
        return false;
    }
    public function show($request)
    {
        $company = $this->query()
            ->select([
                'id',
                'user_id',
                'company_name',
                'activity_domain',
                'vat_id',
                'registration_number',
                'country',
                'county',
                'city',
                'zip_code',
                'address',
                'building',
                'person_name',
                'job_title',
                'phone_number'
            ])
            ->where('id', $request->id)
            ->with(['users'])
            ->first();

        return response()->json($company);
    }
    public function active($request)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->id);
            $user->is_active = !$user->is_active;
            $user->save();
            if ($user->is_active == "true") {
                event(new CompanyActivedEvent($user));
            }
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        return false;
    }

    public function update($request, $company)
    {
        DB::beginTransaction();
        try {
            $company->company_name = $request->company_name;
            $company->activity_domain = $request->activity_domain;
            $company->vat_id = $request->vat_id;
            $company->registration_number = $request->registration_number;
            $company->country = $request->country;
            $company->county = $request->county;
            $company->city = $request->city;
            $company->zip_code = $request->zip_code;
            $company->address = $request->address;
            $company->building = $request->building;
            $company->person_name = $request->person_name;
            $company->job_title = $request->job_title;
            $company->phone_number = $request->phone_number;
            $company->save();
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }
}
