<?php

namespace App\Repositories\Admin;

use App\Models\Support\Support;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SupportRepository extends BaseRepository
{
    public function __construct(Support $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $user = Auth::user();
        $roles = $user->getRoleNames();
        $data = $this->query()
            ->select([
                'id',
                'name',
                'subject',
                'organization',
                'read_by',
                'is_archive'
            ])
            ->where('is_archive', 0)
            ->whereIn('organization', $roles)
            ->with('reader')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('read_by', function ($row) {
                    if ($row->reader) {
                        return $row->reader->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('button', function ($row) {
                    return '<button onclick="showMessageModal(' . $row->id . ')" type="button"
                                    class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#forgetRequest">
                                <i class="fa-solid fa-square-arrow-up-right"></i>
                            </button>';
                })
                ->rawColumns(['read_by', 'button'])
                ->make(true);
        }
        return false;
    }

    public function getArchiveDataTable($request)
    {
        $user = Auth::user();
        $roles = $user->getRoleNames();
        $data = $this->query()
            ->select([
                'id',
                'name',
                'subject',
                'organization',
                'read_by',
                'is_archive'
            ])
            ->where('is_archive', 1)
            ->whereIn('organization', $roles)
            ->with('reader')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('read_by', function ($row) {
                    if ($row->reader) {
                        return $row->reader->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('button', function ($row) {
                    return '<button onclick="showMessageModal(' . $row->id . ')" type="button"
                                    class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#forgetRequest">
                                <i class="fa-solid fa-square-arrow-up-right"></i>
                            </button>';
                })
                ->rawColumns(['read_by', 'button'])
                ->make(true);
        }
        return false;
    }

    public function show($request)
    {
        $userId = Auth::id();
        $message = $this->query()
            ->select([
                'id',
                'name',
                'cod_staff',
                'email',
                'mobile_phone',
                'subject',
                'description',
                'organization',
                'cod_staff',
                'read_by',
                'read_at',
                'created_at',
                'is_archive'
            ])
            ->where('id', $request->id)
            ->first();

        if (is_null($message->read_at)) {
            $message->read_at = Carbon::now();
        }
        $message->read_by = $userId;
        $message->save();
        return $message;
    }

    public function archivedShow($request)
    {
        $message = $this->query()
            ->select([
                'id',
                'name',
                'cod_staff',
                'email',
                'mobile_phone',
                'subject',
                'description',
                'organization',
                'cod_staff',
                'read_by',
                'read_at',
                'created_at',
                'is_archive'
            ])
            ->where('id', $request->id)
            ->first();
        return $message;

    }

    public function archiveMessage($request)
    {
        $message = $this->query()
            ->select('id')
            ->where('id', $request->support_id)
            ->first();
        $message->is_archive = 1;
        $message->save();
    }

    public function reArchiveMessage($request)
    {
        $message = $this->query()
            ->select('id')
            ->where('id', $request->support_id)
            ->first();
        $message->is_archive = 0;
        $message->save();
    }
}
