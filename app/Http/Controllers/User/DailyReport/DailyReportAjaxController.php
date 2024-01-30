<?php

namespace App\Http\Controllers\User\DailyReport;

use App\Http\Controllers\Controller;
use App\Repositories\User\DailyReportRepository;
use Illuminate\Http\Request;

class DailyReportAjaxController extends Controller
{
    protected $postRepository;

    public function __construct(DailyReportRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
}
