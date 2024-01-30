<?php

namespace App\Http\Controllers\Admin\DailyReport;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\DailyReportRepository;
use Illuminate\Http\Request;

class DailyReportAjaxController extends Controller
{
    protected $postRepository;

    public function __construct(DailyReportRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
}
