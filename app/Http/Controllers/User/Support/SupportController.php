<?php

namespace App\Http\Controllers\User\Support;

use App\Http\Controllers\Controller;
use App\Repositories\User\SupportRepository;

class SupportController extends Controller
{
    protected $supportRepository;

    public function __construct(SupportRepository $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }

    public function index()
    {
        return view('user.supports.index');
    }
}
