<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\User\DashboardRepository;
use Illuminate\Http\Request;

class DashboardAjaxController extends Controller
{
    protected $userRepository;

    public function __construct(DashboardRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

}
