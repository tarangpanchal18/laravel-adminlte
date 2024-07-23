<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Repositories\Admin\PageRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PageRequest;

class PageController extends Controller
{
    public function __construct(private PageRepository $PageRepository)
    {
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
        abort(403, "You dont have permission to create");

        $this->PageRepository->create($request->validated());
        return redirect(route('admin.pages.index'))->with('success', config('constants.default_data_insert_msg'));
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
        $this->PageRepository->update($page->id, $request->validated());
        return redirect(route('admin.pages.index'))->with('success', config('constants.default_data_update_msg'));
    }
}
