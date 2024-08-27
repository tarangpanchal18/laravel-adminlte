<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Repositories\Admin\AdminRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct(private AdminRepository $adminRepository)
    {
        //
    }

    public function index(Request $request)
    {
        abort_request_if('view permission module');
        return $request->ajax()
            ? $this->adminRepository->getAsyncListingData($request)
            : view('admin.users.admins.index');
    }

    public function create()
    {
        abort_request_if('view permission module');
        return view('admin.users.admins.alter', [
            'action' => 'Add',
            'roleData' => Role::all(),
            'actionUrl' => route('admin.admins.store'),
        ]);
    }

    public function store(AdminRequest $request)
    {
        abort_request_if('view permission module');
        return $this->adminRepository->create($request->validated());
    }

    public function edit(Admin $admin)
    {
        abort_request_if('view permission module');
        return view('admin.users.admins.alter', [
            'user' => $admin,
            'action' => 'Edit',
            'roleData' => Role::all(),
            'actionUrl' => route('admin.admins.update', $admin),
        ]);
    }

    public function update(AdminRequest $request, Admin $admin)
    {
        abort_request_if('view permission module');
        return $this->adminRepository->update($admin->id, $request->validated());
    }

    public function destroy(Admin $admin)
    {
        abort_request_if('view permission module');
        return $this->adminRepository->delete($admin->id);
    }

    public function handleMassUpdate(Request $request)
    {
        abort_request_if('view permission module');
        $ids = $request->ids;
        $operationType = $request->operationType;
        $this->adminRepository->updateMultiple(Admin::class, $ids, $operationType);

        return response()->json([
            'success' => true,
            'message' => config('constants.default_data_update_msg')
        ]);
    }
}
