<?php

namespace App\Interfaces\Admin;

use Illuminate\Http\Request;

interface MasterInterface
{
    public function getRaw();
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete(int $id);
    public function getAsyncListingData(Request $request);
}
