<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Repositories\Admin\PageRepository;
use App\Services\FilesService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PageRequest;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct(
        private PageRepository $PageRepository,
        private FilesService $fileService
    ) {
        //
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->PageRepository->getAsyncListingData($request);
        }

        return view('admin.pages.index');
    }

    public function create(): View
    {
        return view('admin.pages.alter', [
            'action' => 'Add',
            'actionUrl' => route('admin.pages.store'),
        ]);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $input = $request->validated();
        $input['page_slug'] = Str::slug($input['page_name']);
        $this->PageRepository->create($input);

        return redirect(route('admin.pages.index'))->with('success', 'Data Created Successfully !');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.alter', [
            'page' => $page,
            'action' => 'Edit',
            'actionUrl' => route('admin.pages.update', $page),
        ]);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $validated = $request->validated();
        $validated['page_slug'] = Str::slug($validated['page_name']);
        $this->PageRepository->update($page->id, $validated);

        return redirect(route('admin.pages.index'))->with('success', 'Data Updated Successfully !');
    }
}
