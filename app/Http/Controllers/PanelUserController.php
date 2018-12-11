<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUser;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use App\Role;

class PanelUserController extends Controller
{
    /**
     * @var UserService userService
     */
    private $userService;

    /**
     * PanelUserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = User::paginate(config('phyto.pagination_size'));
        return view('panel.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('panel.users.create', compact('roles'));
    }

    public function store(CreateUser $request)
    {
        $data = $request->all(['name', 'email', 'role']);

        $this->userService->createUser($data['name'], $data['email'], Role::findOrFail($data['role']));

        return redirect()->route('panel.users');

    }

    public function reset(int $id)
    {
        $user = User::findOrFail($id);

        $this->userService->resetPassword($id);

        return back()->with('alert', trans('panel.users.reset_password_alert', ['username' => $user->name]));
    }
}
