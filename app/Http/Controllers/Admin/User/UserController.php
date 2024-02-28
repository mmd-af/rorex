<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Models\User\User;
use App\Repositories\Admin\UserRepository;
use Illuminate\Http\Request;

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

    public function update(Request $request, User $user)
    {
        $this->userRepository->update($request, $user);
        return redirect()->route('admin.users.index');
    }
}
