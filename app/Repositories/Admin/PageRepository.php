<?php

namespace App\Repositories\Admin;

use App\Interfaces\BaseAdminModules;
use App\Models\Page;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PageRepository extends BaseAdminModules
{
    public function getAll()
    {
        return Page::all();
    }

    public function getRaw($filterData = "")
    {
        $query = Page::query();
        if ($filterData['status']) {
            $query = $query->where('status', $filterData['status']);
        }

        return $query;
    }

    public function getById($id)
    {
        return Page::findOrFail($id);
    }

    public function sanitizeData(array $data)
    {
        $data['page_slug'] = Str::slug($data['page_name']);

        return $data;
    }

    public function create(array $data)
    {
        return Page::create($this->sanitizeData($data));
    }

    public function update($id, array $newDetails)
    {
        return Page::whereId($id)->update($this->sanitizeData($newDetails));
    }

    public function delete($id)
    {
        Page::destroy($id);
    }

    public function getAsyncListingData(Request $request)
    {
        $data = $this->getRaw($request?->filterData);
        if (empty($request->order)) {
            $data->latest('id');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('page_name', function($row) {
                return '<a href="'. route('admin.pages.edit', $row->id) .'">'. $row->page_name .'</a>';
            })
            ->editColumn('status', function ($row) {
                return '<button
                        data-id="'. $row->id .'"
                        data-value="'. $row->status .'"
                        data-url="'. route("admin.pages.index") .'"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="'. config('constants.default_status_change_txt') .'"
                        class="changestatus btn btn-sm btn-'. ($row->status == "Active" ? "success" : "danger") .'"
                    >'. $row->status .'</button>' .
                    PHP_EOL;
            })
            ->addColumn('action', function ($row) {
                return '<div>' .
                    '<a data-toggle="tooltip" title="'. config('constants.default_edit_txt') .'" href="' . route('admin.pages.edit', $row->id) . '" class="edit btn btn-success btn-sm mr-2"><i class="fa fa-edit"></i></a>' .
                    PHP_EOL;
            })
            ->editColumn('updated_at', function ($row) {
                return date(config('constants.default_datetime_format'), strtotime($row->updated_at));
            })
            ->rawColumns(['page_name', 'status', 'action'])
            ->make(true);
    }
}
