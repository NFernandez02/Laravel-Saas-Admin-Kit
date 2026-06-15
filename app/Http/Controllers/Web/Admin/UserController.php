<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct(private UserService $service) {}

    public function index(): View
    {
        if (auth()->user()->cannot('viewAny', User::class)) {
            abort(403);
        }
        $users = User::with('role')->
        when(request('search'), function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        if (auth()->user()->cannot('create', User::class)) {
            abort(403);
        }
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        if (auth()->user()->cannot('create', User::class)) {
            abort(403);
        }
        /** @var array{
         * name: string,
         * email: string,
         * password: string,
         * role_id: int
         * } $data
         */
        $data = $request->validated();
        $this->service->create($data);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user): View
    {
        if (auth()->user()->cannot('update', $user)) {
            abort(403);
        }
        $roles = Role::all();

        return view('admin.users.edit', compact(['user', 'roles']));
    }

    public function update(User $user, UpdateUserRequest $request): RedirectResponse
    {
        if (auth()->user()->cannot('update', $user)) {
            abort(403);
        }
        /** @var array{
         * name: string,
         * role_id: int
         * } $data
         */
        $data = $request->validated();
        $this->service->update($user, $data);

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->user()->cannot('delete', $user)) {
            abort(403);
        }
        $this->service->delete($user);

        return redirect()->route('admin.users.index');
    }
}
