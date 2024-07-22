<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Requests\UserReqeust;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->userRepository->getAsyncListingData($request);
        }

        return view('admin.users.index');
    }

    public function create(): View
    {
        return view('admin.users.alter', [
            'action' => 'Add',
            'actionUrl' => route('admin.users.store'),
        ]);
    }

    public function store(UserReqeust $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $this->userRepository->create($validated);

        return redirect(route('admin.users.index'))->with('success', 'Data Created Successfully !');
    }

    public function edit(User $user): View
    {
        return view('admin.users.alter', [
            'user' => $user,
            'action' => 'Edit',
            'actionUrl' => route('admin.users.update', $user),
        ]);
    }

    public function update(UserReqeust $request, User $user): RedirectResponse
    {
        $this->userRepository->update($user->id, $request->validated());
        return redirect(route('admin.users.index'))->with('success', 'Data Updated Successfully !');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect(route('admin.users.index'))->with('success', 'Data Deleted Successfully !');
    }
}
