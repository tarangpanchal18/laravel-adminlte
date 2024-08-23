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

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository) {
        //
    }

    public function index(Request $request): View|JsonResponse
    {
        abort_request_if('view users');
        return $request->ajax()
            ? $this->userRepository->getAsyncListingData($request)
            : view('admin.users.index');
    }

    public function create(): View
    {
        abort_request_if('create users');
        return view('admin.users.alter', [
            'action' => 'Add',
            'actionUrl' => route('admin.users.store'),
        ]);
    }

    public function store(UserReqeust $request): RedirectResponse
    {
        abort_request_if('create users');
        return $this->userRepository->create($request->validated());
    }

    public function edit(User $user): View
    {
        abort_request_if('update users');
        return view('admin.users.alter', [
            'user' => $user,
            'action' => 'Edit',
            'actionUrl' => route('admin.users.update', $user),
        ]);
    }

    public function update(UserReqeust $request, User $user): RedirectResponse|JsonResponse
    {
        abort_request_if('update users');
        return $this->userRepository->update($user->id, $request->validated());
    }

    public function destroy(User $user, Request $request): RedirectResponse|JsonResponse
    {
        abort_request_if('delete users');
        return $this->userRepository->delete($user->id);
    }

    public function handleMassUpdate(Request $request)
    {
        abort_request_if('update users');
        $ids = $request->ids;
        $operationType = $request->operationType;
        $this->userRepository->updateMultiple(User::class, $ids, $operationType);

        return response()->json([
            'success' => true,
            'message' => config('constants.default_data_update_msg')
        ]);
    }
}
