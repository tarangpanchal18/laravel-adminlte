<?php

namespace App\Interfaces;

abstract class BaseAdminModules
{
    //used for multiple checkbox selection operation in listing
    const MARK_ACTIVE = 1;
    const MARK_INACTIVE = 2;
    const MARK_DELETED = 3;

    abstract public function getAll();

    abstract public function getRaw($filterData = []);

    abstract public function getById($id);

    abstract function sanitizeData(array $data);

    abstract public function create(array $data);

    abstract public function update($id, array $data);

    abstract public function delete(int $id);

    public function updateMultiple(string $modal, array $ids, int $operationType)
    {
        if ($operationType == self::MARK_ACTIVE) {
            return $modal::whereIn('id', $ids)->update(['status' => 1]);
        } elseif ($operationType == self::MARK_INACTIVE) {
            return $modal::whereIn('id', $ids)->update(['status' => 0]);
        } elseif ($operationType == self::MARK_DELETED) {
            return $modal::whereIn('id', $ids)->delete();
        }
    }
}
