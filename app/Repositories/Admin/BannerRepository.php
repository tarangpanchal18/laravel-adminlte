<?php

namespace App\Repositories\Admin;

use App\Interfaces\Admin\MasterInterface;
use App\Models\Banner;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class BannerRepository implements MasterInterface
{
    public function getAll()
    {
        return Banner::all();
    }

    public function getRaw($filterData = "")
    {
        $query = Banner::query();
        if ($filterData['status']) {
            $query = $query->where('status', $filterData['status']);
        }

        return $query;
    }

    public function getById($id)
    {
        return Banner::findOrFail($id);
    }

    public function delete($id)
    {
        Banner::destroy($id);
    }

    public function create(array $data)
    {
        return Banner::create($data);
    }

    public function update($id, array $newDetails)
    {
        return Banner::whereId($id)->update($newDetails);
    }

    public function getAsyncListingData(Request $request)
    {
        $data = $this->getRaw($request?->filterData);
        if (empty($request->order)) {
            $data->latest('id');
        }

        return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function($row) {
                    return '<button
                        data-id="'. $row->id .'"
                        data-value="'. $row->status .'"
                        data-url="'. route("admin.banner.index") .'"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="'. config('constants.default_status_change_txt') .'"
                        class="changestatus btn btn-sm btn-outline-'. ($row->status == "1" ? "success" : "danger") .'"
                    >'. ($row->status == "1" ? "Active" : "InActive") .'</button>' .
                    PHP_EOL;
                })
                ->addColumn('action', function($row){
                        return '<div style="width: 150px">' .
                        '<a data-toggle="tooltip" title="'. config('constants.default_edit_txt') .'" href="'. route('admin.banner.edit', $row->id) .'" class="edit btn btn-success btn-sm"><i class="fa fa-edit"></i></a>&nbsp;' .
                        '<button data-toggle="tooltip" title="'. config('constants.default_delete_txt') .'" onclick="removeData('. $row->id. ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>' .
                        '<div>' .
                        PHP_EOL;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }
}
