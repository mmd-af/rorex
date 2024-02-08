<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\SupportRepository;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    protected $supportRepository;

    public function __construct(SupportRepository $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }

    public function index()
    {
        return view('admin.supports.index');
    }

    public function archiveMessage(Request $request)
    {
        $this->supportRepository->archiveMessage($request);
        return view('admin.supports.index');
    }
}
