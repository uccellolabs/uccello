<?php

namespace Uccello\Core\Support\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Uccello\Core\Models\Relatedlist;

trait RelatedlistTrait
{
    /**
     * Returns record label
     * Default: id
     *
     * @return string
     */
    public function getRecordLabelAttribute() : string
    {
        return $this->getKey();
    }

    /**
     * Retrieves related records linked by an entity field
     *
     * @param Relatedlist $relatedList
     * @param integer $recordId
     * @param Builder|null $query
     * @param int $start
     * @return Collection
     */
    public function getDependentList(Relatedlist $relatedList, int $recordId, ?Builder $query = null)
    {
        // Related field
        $relatedField = $relatedList->relatedField;
        $filter = ['order' => request('order')];

        $query = $query->where(function (Builder $_query) use ($recordId, $relatedList, $relatedField) {
            // Search by id
            $_query->where($relatedField->column, $recordId);

            // Search by uuid
            $record = ucrecord($recordId, $relatedList->module->model_class);
            if ($record->uuid ?? false) {
                $_query->orWhere($relatedField->column, $record->uuid);
            }
        })
        ->filterBy($filter);

        return $query;
    }

    /**
     * Counts related records linked by an entity field
     *
     * @param Relatedlist $relatedList
     * @param integer $recordId
     * @return int
     */
    public function getDependentListCount(Relatedlist $relatedList, int $recordId) : int
    {
        // Model
        $relatedModel = $relatedList->relatedModule->model_class;

        // Related field
        $relatedField = $relatedList->relatedField;

        return $relatedModel::where(function (Builder $_query) use ($recordId, $relatedList, $relatedField) {
            // Search by id
            $_query->where($relatedField->column, $recordId);

            // Search by uuid
            $record = ucrecord($recordId, $relatedList->module->model_class);
            if ($record->uuid ?? false) {
                $_query->orWhere($relatedField->column, $record->uuid);
            }
        })
        ->count();
    }

    /**
     * Get ids of related records for n-1 relations
     *
     * @param Relatedlist $relatedList
     * @param integer $recordId
     * @return Collection
     */
    public function getDependentListRecordIds(Relatedlist $relatedList, int $recordId) : Collection
    {
        // Get record
        $modelClass = $relatedList->module->model_class;
        $record = $modelClass::find($recordId);

        // Get related key name
        $relatedModel = new $relatedList->relatedModule->model_class;
        $relatedTable = $relatedModel->table;
        $relatedPrimaryKey = $relatedModel->getKeyName();

        // Get related records ids
        $relationName = $relatedList->relationName;
        return $record->$relationName()->pluck("$relatedTable.$relatedPrimaryKey"); //TODO: Check if it works with uuid
    }

    /**
     * Retrieves related records for n-n relations
     *
     * @param Relatedlist $relatedList
     * @param integer $recordId
     * @param Builder|null $query
     * @param int $start
     * @return Collection
     */
    public function getRelatedList(Relatedlist $relatedList, int $recordId, ?Builder $query = null)
    {
        $modelClass = $relatedList->module->model_class;
        $relationName = $relatedList->relationName;

        $record = $modelClass::find($recordId);
        $filter = ['order' => request('order')];

        $query = $record->$relationName()
                        ->filterBy($filter);

        return $query;
    }

    /**
     * Counts related records for n-n relations
     *
     * @param Relatedlist $relatedList
     * @param integer $recordId
     * @return int
     */
    public function getRelatedListCount(Relatedlist $relatedList, int $recordId) : int
    {
        return $this->getRelatedList($relatedList, $recordId)->count();
    }

    /**
     * Get ids of related records for n-n relations
     *
     * @param Relatedlist $relatedList
     * @param integer $recordId
     * @return Collection
     */
    public function getRelatedListRecordIds(Relatedlist $relatedList, int $recordId) : Collection
    {
        // Get record
        $modelClass = $relatedList->module->model_class;
        $record = $modelClass::find($recordId);

        // Get related key name
        $relatedModel = new $relatedList->relatedModule->model_class;
        $relatedTable = $relatedModel->table;
        $relatedPrimaryKey = $relatedModel->getKeyName();

        // Get related records ids
        $relationName = $relatedList->relationName;
        return $record->$relationName()->pluck("$relatedTable.$relatedPrimaryKey");
    }
}
