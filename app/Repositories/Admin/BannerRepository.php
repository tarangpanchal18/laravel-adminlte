<?php

namespace App\Repositories\Admin;

use App\Facades\CustomLogger;
use App\Interfaces\BaseAdminModules;
use App\Models\Banner;
use App\Services\FilesService;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class BannerRepository extends BaseAdminModules
{
    const BASE_URL = 'admin.banner';

    const MODEL = Banner::class;

    public function __construct(private FilesService $fileService)
    {
        //
    }

    public function getAll()
    {
        return self::MODEL::all();
    }

    public function getRaw($filterData = "")
    {
        $query = self::MODEL::query();
        if ($filterData['status']) {
            $query = $query->where('status', $filterData['status']);
        }

        return $query;
    }

    public function getById($id)
    {
        return self::MODEL::findOrFail($id);
    }

    public function sanitizeData(array $data)
    {
        if (isset($data['image'])) {
            $fileName = $this->fileService->generateFileName('banner', $data['image']->getClientOriginalExtension());
            $this->fileService->handleUpload($data['image'], self::MODEL::UPLOAD_PATH, $fileName);
            $data['image'] = $fileName;
        }

        return $data;
    }

    public function create(array $data)
    {
        try {
            self::MODEL::create($this->sanitizeData($data));
            return redirect(route('admin.banner.index'))->with('success', config('constants.default_data_insert_msg'));
        } catch (\Throwable $th) {
            CustomLogger::write('Banner', ERROR, $th->getMessage());
            return redirect(route(self::BASE_URL . '.index'))->with('error', config('constants.default_data_failed_msg'));
        }
    }

    public function update($id, array $newDetails)
    {
        try {
            $banner = self::MODEL::whereId($id)->first();
            $oldImage = $banner->image;
            $status = $banner->update($this->sanitizeData($newDetails));

            if ($status && isset($newDetails['image'])) {
                $this->fileService->handleRemoveFile(self::MODEL::UPLOAD_PATH, $oldImage);
            }

            return request()->ajax()
                ? response()->json(['success' => true, 'message' => config('constants.default_data_update_msg')])
                : redirect(route('admin.banner.index'))->with('success', config('constants.default_data_update_msg'));

        } catch (\Throwable $th) {
            CustomLogger::write('Banner', ERROR, $th->getMessage());
            return redirect(route(self::BASE_URL . '.index'))->with('error', config('constants.default_data_failed_msg'));
        }
    }

    public function delete(int $id)
    {
        try {
            $banner = self::MODEL::whereId($id)->first();
            if ($banner->image) {
                $this->fileService->handleRemoveFile(self::MODEL::UPLOAD_PATH, $banner->image);
            }
            $banner->delete();

            return request()->ajax()
                ? response()->json(['success' => true, 'message' => config('constants.default_data_deleted_msg')])
                : redirect(route('admin.banner.index'))->with('success', config('constants.default_data_deleted_msg'));

        } catch (\Throwable $th) {
            CustomLogger::write('Banner', ERROR, $th->getMessage());
            return redirect(route(self::BASE_URL . '.index'))->with('error', config('constants.default_data_failed_msg'));
        }
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
                        data-url="'. route(self::BASE_URL . ".index") .'"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="'. config('constants.default_status_change_txt') .'"
                        class="changestatus btn btn-sm btn-outline-'. ($row->status == "1" ? "success" : "danger") .'"
                    >'. ($row->status == "1" ? "Active" : "InActive") .'</button>' .
                    PHP_EOL;
                })
                ->addColumn('action', function($row){
                        return '<div style="width: 150px">' .
                        '<a data-toggle="tooltip" title="'. config('constants.default_edit_txt') .'" href="'. route(self::BASE_URL . '.edit', $row->id) .'" class="edit btn btn-success btn-sm"><i class="fa fa-edit"></i></a>&nbsp;' .
                        '<button data-toggle="tooltip" title="'. config('constants.default_delete_txt') .'" onclick="removeData('. $row->id. ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>' .
                        '<div>' .
                        PHP_EOL;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }
}
