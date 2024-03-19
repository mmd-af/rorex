<?php

namespace App\Repositories\User;

use App\Mail\RequestMail;
use App\Models\Support\Support;
use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class SupportRepository extends BaseRepository
{
    public function __construct(Support $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $userId = Auth::id();
        $userEmail = Auth::user()->email;
        $data = $this->query()
            ->select([
                'id',
                'email',
                'subject',
                'description',
                'organization',
                'cod_staff',
                'read_at',
                'created_at',
                'is_archive'
            ])
            ->where('cod_staff', $userId)
            ->orWhere('email', $userEmail)
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('created_at', function ($row) {
                    $originalDate = Carbon::parse($row->created_at);
                    return $originalDate->format('Y-m-d');
                })
                ->rawColumns(['description', 'action'])
                ->make(true);
        }
        return false;
    }

    public function getRoles($request)
    {
        return Role::query()
            ->select([
                'id',
                'name'
            ])
            ->get();

    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $support = new Support();
            $support->name = $request->name;
            $support->email = $request->email;
            $support->mobile_phone = $request->mobile_phone;
            $support->subject = $request->subject;
            $support->description = $request->description;
            $support->organization = $request->organization;
            $support->cod_staff = (int)$request->cod_staff;
            $support->save();
            $role = Role::query()
                ->select(['id', 'name'])
                ->where('name', $request->organization)
                ->first();

            if ($role) {
                $usersWithRole = User::whereHas('roles', function ($query) use ($role) {
                    $query->where('role_id', $role->id);
                })->get();
                foreach ($usersWithRole as $user) {
                    if ($user->email_verified_at !== null) {
                        Mail::to($user->email)->send(new RequestMail($request->subject, $request->description));
                    }
                }
            }
            DB::commit();
            Session::flash('message', 'Your Request Send Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }
}
