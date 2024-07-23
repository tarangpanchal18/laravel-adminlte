<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\Admin\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{

    public function __construct(private CategoryRepository $categoryRepository)
    {
        //
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->categoryRepository->getAsyncListingData($request);
        }

        return view('admin.category.index', [
            'categoryData' => $this->categoryRepository->getParentCategory()
        ]);
    }

    public function create(): View
    {
        return view('admin.category.alter', [
            'action' => 'Add',
            'actionUrl' => route('admin.category.store'),
            'categoryData' => $this->categoryRepository->getParentCategory(),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->categoryRepository->create($request->validated());
        return redirect(route('admin.category.index'))->with('success', config('constants.default_data_insert_msg'));
    }

    public function edit(Category $category): View
    {
        return view('admin.category.alter', [
            'category' => $category,
            'action' => 'Edit',
            'actionUrl' => route('admin.category.update', $category),
            'categoryData' => $this->categoryRepository->getParentCategory($category->id),
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse|JsonResponse
    {
        $this->categoryRepository->update($category->id, $request->validated());
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => config('constants.default_data_update_msg')]);
        }

        return redirect(route('admin.category.index'))->with('success', config('constants.default_data_update_msg'));
    }

    public function destroy(Category $category, Request $request): RedirectResponse|JsonResponse
    {
        $this->categoryRepository->delete($category->id);
        if ($request->ajax()) {
            return response()->json(['success' => true,'message' => config('constants.default_data_deleted_msg')]);
        }

        return redirect(route('admin.category.index'))->with('success', config('constants.default_data_deleted_msg'));
    }
}
