<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\Admin\CategoryRepository;
use App\Services\FilesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{

    public function __construct(
        private CategoryRepository $categoryRepository,
        private FilesService $fileService
    ) {
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
        $validated = $request->validated();
        if ($request->file('image')) {
            $validated['image'] = $this->fileService->generateFileName('cat', $request->file('image')->getClientOriginalExtension());
            $this->fileService->handleUpload($request->file('image'),Category::UPLOAD_PATH,$validated['image']);
        }
        $this->categoryRepository->create($validated);

        return redirect(route('admin.category.index'))->with('success', 'Data Created Successfully !');
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

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->file('image')) {
            if ($category->image) {
                $this->fileService->handleRemoveFile(Category::UPLOAD_PATH, $category->image);
            }
            $validated['image'] = $this->fileService->generateFileName('cat', $request->file('image')->getClientOriginalExtension());
            $this->fileService->handleUpload($request->file('image'), Category::UPLOAD_PATH, $validated['image']);
        }
        $this->categoryRepository->update($category->id, $validated);

        return redirect(route('admin.category.index'))->with('success', 'Data Updated Successfully !');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->image) {
            $this->fileService->handleRemoveFile(Category::UPLOAD_PATH, $category->image);
        }
        $category->delete();

        return redirect(route('admin.category.index'))->with('success', 'Data Deleted Successfully !');
    }
}
