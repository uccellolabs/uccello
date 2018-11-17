<?php

namespace Uccello\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as DefaultModel;
use Illuminate\Database\Eloquent\Builder ;
use Uccello\Core\Models\Relatedlist;
use Uccello\Core\Models\Relation;
use Uccello\Core\Models\Module;

class Model extends DefaultModel
{
    protected $tablePrefix;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set table prefix
        $this->setTablePrefix();

        // Set table name
        $this->setTableName();
    }

    protected function setTablePrefix()
    {
        $this->tablePrefix = '';
    }

    protected function setTableName()
    {
        if($this->table)
        {
            $this->table = $this->tablePrefix . $this->table;
        }
    }

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
     * @param Module $relatedModule
     * @param integer $recordId
     * @param Builder $query
     * @return Builder
     */
    public function getDependentList(Relatedlist $relatedList, Module $relatedModule, int $recordId, Builder $query) : Builder
    {
        // Related field
        $relatedField = $relatedList->relatedField;

        return $query->where($relatedField->column, $recordId);
    }

    /**
     * Counts related records linked by an entity field
     *
     * @param Relatedlist $relatedList
     * @param Module $relatedModule
     * @param integer $recordId
     * @return int
     */
    public function getDependentListCount(Relatedlist $relatedList, Module $relatedModule, int $recordId) : int
    {
        // Related module
        $relatedModule = $relatedList->relatedModule;

        // Model
        $relatedModel = new $relatedModule->model_class;

        // Related field
        $relatedField = $relatedList->relatedField;

        return $relatedModel->where($relatedField->column, $recordId)->count();
    }

    /**
     * Retrieves related records for n-n relations
     *
     * @param Relatedlist $relatedList
     * @param Module $relatedModule
     * @param integer $recordId
     * @param Builder $query
     * @return Builder
     */
    public function getRelatedList(Relatedlist $relatedList, Module $relatedModule, int $recordId, Builder $query) : Builder
    {
        // Get related record ids
        $relatedRecordIds = Relation::where('relatedlist_id', $relatedList->id)
            ->where('related_module_id', $relatedModule->id)
            ->where('record_id', $recordId)
            ->pluck('id');

        // Related model
        $relatedModel = new $relatedModule->model_class;

        // Returns related records
        return $relatedModel::whereIn('id', $relatedRecordIds);
    }

    /**
     * Counts related records for n-n relations
     *
     * @param Relatedlist $relatedList
     * @param Module $relatedModule
     * @param integer $recordId
     * @return int
     */
    public function getRelatedListCount(Relatedlist $relatedList, Module $relatedModule, int $recordId) : int
    {
        return Relation::where('relatedlist_id', $relatedList->id)
            ->where('related_module_id', $relatedModule->id)
            ->where('record_id', $recordId)
            ->count();
    }
}