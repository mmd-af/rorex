<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\DashboardRepository;
use Illuminate\Http\Request;

class DashboardAjaxController extends Controller
{
    protected $userRepository;

    public function __construct(DashboardRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

}
