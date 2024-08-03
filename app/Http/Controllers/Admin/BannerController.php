<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use App\Repositories\Admin\BannerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{

    public function __construct(private BannerRepository $bannerRepository) {
        //
    }

    public function index(Request $request): View|JsonResponse
    {
        return $request->ajax()
            ? $this->bannerRepository->getAsyncListingData($request)
            : view('admin.banner.index', ['upload_path' => Banner::UPLOAD_PATH]);
    }

    public function create(): View
    {
        return view('admin.banner.alter', [
            'action' => 'Add',
            'actionUrl' => route('admin.banner.store'),
        ]);
    }

    public function store(BannerRequest $request): RedirectResponse
    {
        $this->bannerRepository->create($request->validated());
        return redirect(route('admin.banner.index'))->with('success', config('constants.default_data_insert_msg'));
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.alter', [
            'banner' => $banner,
            'action' => 'Edit',
            'actionUrl' => route('admin.banner.update', $banner),
        ]);
    }

    public function update(BannerRequest $request, Banner $banner): RedirectResponse|JsonResponse
    {
        $this->bannerRepository->update($banner->id, $request->validated());
        return $request->ajax()
            ? response()->json(['success' => true, 'message' => config('constants.default_data_update_msg')])
            : redirect(route('admin.banner.index'))->with('success', config('constants.default_data_update_msg'));
    }

    public function destroy(Banner $banner, Request $request): RedirectResponse|JsonResponse
    {
        $this->bannerRepository->delete($banner->id);
        return $request->ajax()
            ? response()->json(['success' => true,'message' => config('constants.default_data_deleted_msg')])
            : redirect(route('admin.banner.index'))->with('success', config('constants.default_data_deleted_msg'));
    }
}
