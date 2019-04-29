<?php

namespace Uccello\Core\Support\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Uccello\Core\Models\Relatedlist;
use Uccello\Core\Models\Relation;

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
     * @param Builder $query
     * @param int $start
     * @param int $length
     * @return Collection
     */
    public function getDependentList(Relatedlist $relatedList, int $recordId, Builder $query, int $length = 15)
    {
        // Related field
        $relatedField = $relatedList->relatedField;

        return $query->where($relatedField->column, $recordId)
            ->paginate($length);
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
        // Related module
        $relatedModule = $relatedList->relatedModule;

        // Model
        $relatedModel = new $relatedList->relatedModule->model_class;

        // Related field
        $relatedField = $relatedList->relatedField;

        return $relatedModel->where($relatedField->column, $recordId)
            ->count();
    }

    /**
     * Retrieves related records for n-n relations
     *
     * @param Relatedlist $relatedList
     * @param integer $recordId
     * @param Builder $query
     * @param int $start
     * @param int $length
     * @return Collection
     */
    public function getRelatedList(Relatedlist $relatedList, int $recordId, Builder $query, int $length = 15)
    {
        // Get related record ids
        $relations = Relation::where('relatedlist_id', $relatedList->id)
            ->where('module_id', $relatedList->module_id)
            ->where('related_module_id', $relatedList->related_module_id)
            ->where('record_id', $recordId)
            ->paginate($length);

        // Related model
        $relatedModel = new $relatedList->relatedModule->model_class;

        $relations->getCollection()->transform(function ($relation) use ($relatedModel) {
            $record = $relatedModel::find($relation->related_record_id);
            $record->relation_id = $relation->id; // Add relation id

            return $record;
        });

        return $relations;
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
        return Relation::where('relatedlist_id', $relatedList->id)
            ->where('module_id', $relatedList->module_id)
            ->where('related_module_id', $relatedList->related_module_id)
            ->where('record_id', $recordId)
            ->count();
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
        return Relation::where('relatedlist_id', $relatedList->id)
            ->where('module_id', $relatedList->module_id)
            ->where('related_module_id', $relatedList->related_module_id)
            ->where('record_id', $recordId)
            ->pluck('related_record_id');
    }
}