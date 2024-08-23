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
        abort_request_if('view website banner');
        return $request->ajax()
            ? $this->bannerRepository->getAsyncListingData($request)
            : view('admin.banner.index', ['upload_path' => Banner::UPLOAD_PATH]);
    }

    public function create(): View
    {
        abort_request_if('create website banner');
        return view('admin.banner.alter', [
            'action' => 'Add',
            'actionUrl' => route('admin.banner.store'),
        ]);
    }

    public function store(BannerRequest $request): RedirectResponse
    {
        abort_request_if('create website banner');
        return $this->bannerRepository->create($request->validated());
    }

    public function edit(Banner $banner)
    {
        abort_request_if('update website banner');
        return view('admin.banner.alter', [
            'banner' => $banner,
            'action' => 'Edit',
            'actionUrl' => route('admin.banner.update', $banner),
        ]);
    }

    public function update(BannerRequest $request, Banner $banner): RedirectResponse|JsonResponse
    {
        abort_request_if('update website banner');
        return $this->bannerRepository->update($banner->id, $request->validated());
    }

    public function destroy(Banner $banner, Request $request): RedirectResponse|JsonResponse
    {
        abort_request_if('delete website banner');
        return $this->bannerRepository->delete($banner->id);
    }
}
