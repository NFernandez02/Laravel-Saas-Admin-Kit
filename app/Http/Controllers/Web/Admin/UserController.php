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
        $this->authorize('viewAny', User::class);
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
        $this->authorize('create', User::class);
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);
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
        $this->authorize('update', $user);
        $roles = Role::all();

        return view('admin.users.edit', compact(['user', 'roles']));
    }

    public function update(User $user, UpdateUserRequest $request): RedirectResponse
    {
        $this->authorize('update', $user);
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
        $this->authorize('delete', $user);
        $this->service->delete($user);

        return redirect()->route('admin.users.index');
    }
}
