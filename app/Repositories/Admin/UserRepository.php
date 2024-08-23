<?php

namespace App\Repositories\Admin;

use App\Facades\CustomLogger;
use App\Interfaces\BaseAdminModules;
use App\Jobs\WelcomeUserJob;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseAdminModules
{

    const BASE_URL = 'admin.users';

    const MODEL = User::class;

    public function getAll()
    {
        return self::MODEL::all();
    }

    public function getRaw($filterData = [])
    {
        $query = self::MODEL::query();

        if (isset($filterData['status'])) {
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
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (isset($data['country'])) {
            $country = $data['country'];
            $countryLookup = Country::where('iso2', $country)->first();
            if ($countryLookup) {
                $data['country_id'] = $countryLookup->id;
            }
        }

        unset($data['country']);

        return $data;
    }

    public function create(array $data) {
        try {
            $user = self::MODEL::create($this->sanitizeData($data));

            if (config('mail.enable_user_mail_on_register')) {
                WelcomeUserJob::dispatch($user);
            }

            return redirect(route(self::BASE_URL . '.index'))->with('success', config('constants.default_data_insert_msg'));
        } catch (\Throwable $th) {
            CustomLogger::write('User', ERROR, $th->getMessage());
            return redirect(route(self::BASE_URL . '.index'))->with('error', config('constants.default_data_failed_msg'));
        }
    }

    public function update($id, array $newDetails)
    {
        try {
            self::MODEL::whereId($id)->update($this->sanitizeData($newDetails));

            return request()->ajax()
            ? response()->json(['success' => true, 'message' => config('constants.default_data_update_msg')])
            : redirect(route(self::BASE_URL . '.index'))->with('success', config('constants.default_data_update_msg'));
        } catch (\Throwable $th) {
            CustomLogger::write('User', ERROR, $th->getMessage());
            return redirect(route(self::BASE_URL . '.index'))->with('error', config('constants.default_data_failed_msg'));
        }
    }

    public function delete($id)
    {
        try {
            self::MODEL::destroy($id);

            return request()->ajax()
                ? response()->json(['success' => true, 'message' => config('constants.default_data_deleted_msg')])
                : redirect(route(self::BASE_URL . '.index'))->with('success', config('constants.default_data_deleted_msg'));
        } catch (\Throwable $th) {
            CustomLogger::write('User', ERROR, $th->getMessage());
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
                ->addColumn('cb', function($row) {
                    return '<input type="checkbox" name="multi-select-cb" class="multi-select" data-id="'. $row->id .'">';
                })
                ->addColumn('name', function($row) {
                    return '<a href="'. route(self::BASE_URL . '.edit', $row->id) .'">'. $row->name .'</a>';
                })
                ->editColumn('status', function($row) {
                    return '<button
                        data-id="'. $row->id .'"
                        data-value="'. $row->status .'"
                        data-url="'. route(self::BASE_URL. ".index") .'"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="'. config('constants.default_status_change_txt') .'"
                        class="changestatus btn btn-sm btn-outline-'. ($row->status == "1" ? "success" : "danger") .'"
                    >'. ($row->status == "1" ? "Active" : "InActive") .'</button>' .
                    PHP_EOL;
                })
                ->addColumn('action', function($row){
                        return '<div>' .
                        '<a data-toggle="tooltip" title="'. config('constants.default_edit_txt') .'" href="'. route(self::BASE_URL . '.edit', $row->id) .'" class="edit btn btn-default btn-sm"><i class="fa fa-edit"></i></a>&nbsp;' .
                        '<button data-toggle="tooltip" title="Delete Data" onclick="removeData('. $row->id. ')" class="edit btn btn-default btn-sm"><i class="fa fa-trash"></i></button>' .
                        '<div>' .
                        PHP_EOL;
                })
                ->rawColumns(['cb', 'name', 'status', 'action'])
                ->make(true);
    }

    public function getTotalCount(array $where = [])
    {
        return self::MODEL::where($where)->count();
    }
}
