<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Repositories\Admin\UserRepository;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return view('admin.users.index');
    }

    public function import(UserUpdateRequest $request)
    {
        $this->userRepository->import($request);
        return redirect()->route('admin.users.index')->with([
            'error' => session('error'),
            'message' => session('message'),
        ]);
    }

}
