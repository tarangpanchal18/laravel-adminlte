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
        return $request->ajax()
            ? $this->categoryRepository->getAsyncListingData($request)
            : view('admin.category.index', ['categoryData' => $this->categoryRepository->getParentCategory()]);
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
        return $this->categoryRepository->create($request->validated());
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
        return $this->categoryRepository->update($category->id, $request->validated());
    }

    public function destroy(Category $category, Request $request): RedirectResponse|JsonResponse
    {
        return $this->categoryRepository->delete($category->id);
    }

    public function handleMassUpdate(Request $request)
    {
        $ids = $request->ids;
        $operationType = $request->operationType;
        $this->categoryRepository->updateMultiple(Category::class, $ids, $operationType);

        return response()->json([
            'success' => true,
            'message' => config('constants.default_data_update_msg')
        ]);
    }
}
