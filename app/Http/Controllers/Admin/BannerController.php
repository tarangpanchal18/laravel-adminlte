<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use App\Repositories\Admin\BannerRepository;
use App\Services\FilesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{

    public function __construct(private BannerRepository $bannerRepository, private FilesService $fileService) {
        $this->bannerRepository = $bannerRepository;
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->bannerRepository->getAsyncListingData($request);
        }

        return view('admin.banner.index', [
            'upload_path' => Banner::UPLOAD_PATH
        ]);
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
        $validated = $request->validated();
        if ($request->file('image')) {
            $validated['image'] = $this->fileService->generateFileName('banner', $request->file('image')->getClientOriginalExtension());
            $this->fileService->handleUpload($request->file('image'), Banner::UPLOAD_PATH, $validated['image']);
        }
        $this->bannerRepository->create($validated);

        return redirect(route('admin.banner.index'))->with('success', 'Data Created Successfully !');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.alter', [
            'banner' => $banner,
            'action' => 'Edit',
            'actionUrl' => route('admin.banner.update', $banner),
        ]);
    }

    public function update(BannerRequest $request, Banner $banner)
    {
        $validated = $request->validated();
        if ($request->file('image')) {
            $this->fileService->handleRemoveFile(Banner::UPLOAD_PATH, $banner->image);
            $validated['image'] = $this->fileService->generateFileName('banner', $request->file('image')->getClientOriginalExtension());
            $this->fileService->handleUpload($request->file('image'), Banner::UPLOAD_PATH, $validated['image']
            );
        }
        $this->bannerRepository->update($banner->id, $validated);

        return redirect(route('admin.banner.index'))->with('success', 'Data Updated Successfully !');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            $this->fileService->handleRemoveFile(Banner::UPLOAD_PATH, $banner->image);
        }
        $banner->delete();

        return redirect(route('admin.banner.index'))->with('success', 'Data Deleted Successfully !');
    }
}
