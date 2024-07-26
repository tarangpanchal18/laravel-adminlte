<?php

namespace App\Repositories\Admin;

use App\Interfaces\BaseAdminModules;
use App\Models\Category;
use App\Services\FilesService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryRepository extends BaseAdminModules
{

    public function __construct(private FilesService $fileService)
    {
        //
    }

    public function getAll()
    {
        return Category::all();
    }

    /**
     * @param int $except [Single Id to be ignored]
     */
    public function getParentCategory($igore = '')
    {
        $data = Category::whereNull('parent_id');
        if ($igore) {
            $data->where('id', '!=', $igore);
        }

        return $data->get();
    }

    public function getRaw($filterData = "")
    {
        $query = Category::query();
        if (isset($filterData['status'])) {
            $query = $query->where('status', $filterData['status']);
        }
        if ($filterData['category']) {
            $query = $query->where('parent_id', $filterData['category']);
        }

        return $query;
    }

    public function getById($id)
    {
        return Category::findOrFail($id);
    }

    public function sanitizeData(array $data)
    {
        if (isset($data['image'])) {
            $fileName = $this->fileService->generateFileName('cat', $data['image']->getClientOriginalExtension());
            $this->fileService->handleUpload($data['image'], Category::UPLOAD_PATH, $fileName);
            $data['image'] = $fileName;
        }

        return $data;
    }

    public function create(array $data)
    {
        return Category::create($this->sanitizeData($data));
    }

    public function update($id, array $newDetails)
    {
        $category = Category::whereId($id)->first();
        $oldImage = $category->image;
        $status = $category->update($this->sanitizeData($newDetails));

        if ($status && isset($newDetails['image'])) {
            $this->fileService->handleRemoveFile(Category::UPLOAD_PATH, $oldImage);
        }

        return $status;
    }

    public function delete($id)
    {
        $category = Category::whereId($id)->first();
        if ($category->image) {
            $this->fileService->handleRemoveFile(Category::UPLOAD_PATH, $category->image);
        }

        if ($category->parent_id == null) {
            $childs = $category->children->pluck('id')->toArray();
            Category::whereIn('id', $childs)->delete();
        }

        return $category->delete();
    }

    public function getAsyncListingData(Request $request)
    {
        $data = $this->getRaw($request?->filterData);
        if (empty($request->order)) {
            $data->latest('id');
        }

        return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('cb', function($row) {
                    return '<input type="checkbox" name="multi-select-cb" class="multi-select" data-id="'. $row->id .'">';
                })
                ->editColumn('name', function($row) {
                    return '<a href="'. route('admin.category.edit', $row->id) .'">'. $row->name .'</a>';
                })
                ->editColumn('status', function($row) {
                    return '<button
                        data-id="'. $row->id .'"
                        data-value="'. $row->status .'"
                        data-url="'. route("admin.category.index") .'"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="'. config('constants.default_status_change_txt') .'"
                        class="changestatus btn btn-sm btn-outline-'. ($row->status == "1" ? "success" : "danger") .'"
                    >'. ($row->status == "1" ? "Active" : "InActive") .'</button>' .
                    PHP_EOL;
                })
                ->addColumn('parent', function($row) {
                    return ($row?->parent->name ? $row->parent->name : 'N/A');
                })
                ->addColumn('action', function($row) {
                        return '<div style="width: 150px">' .
                        '<a data-toggle="tooltip" title="'. config('constants.default_edit_txt') .'" href="'. route('admin.category.edit', $row->id) .'" class="edit btn btn-success btn-sm"><i class="fa fa-edit"></i></a>&nbsp;' .
                        '<button data-toggle="tooltip" title="'. config('constants.default_delete_txt') .'" onclick="removeData('. $row->id. ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>' .
                        '<div>' .
                        PHP_EOL;
                })
                ->rawColumns(['cb', 'name', 'status', 'action'])
                ->make(true);
    }
}
