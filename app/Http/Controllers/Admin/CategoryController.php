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
        abort_request_if('view category');
        return $request->ajax()
            ? $this->categoryRepository->getAsyncListingData($request)
            : view('admin.category.index', ['categoryData' => $this->categoryRepository->getParentCategory()]);
    }

    public function create(): View
    {
        abort_request_if('create category');
        return view('admin.category.alter', [
            'action' => 'Add',
            'actionUrl' => route('admin.category.store'),
            'categoryData' => $this->categoryRepository->getParentCategory(),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        abort_request_if('create category');
        return $this->categoryRepository->create($request->validated());
    }

    public function edit(Category $category): View
    {
        abort_request_if('update category');
        return view('admin.category.alter', [
            'category' => $category,
            'action' => 'Edit',
            'actionUrl' => route('admin.category.update', $category),
            'categoryData' => $this->categoryRepository->getParentCategory($category->id),
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse|JsonResponse
    {
        abort_request_if('update category');
        return $this->categoryRepository->update($category->id, $request->validated());
    }

    public function destroy(Category $category, Request $request): RedirectResponse|JsonResponse
    {
        abort_request_if('delete category');
        return $this->categoryRepository->delete($category->id);
    }

    public function handleMassUpdate(Request $request)
    {
        abort_request_if('update category');
        $ids = $request->ids;
        $operationType = $request->operationType;
        $this->categoryRepository->updateMultiple(Category::class, $ids, $operationType);

        return response()->json([
            'success' => true,
            'message' => config('constants.default_data_update_msg')
        ]);
    }
}
