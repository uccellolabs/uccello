<?php

namespace Uccello\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as DefaultModel;
use Uccello\Core\Models\Relatedlist;
use Illuminate\Database\Eloquent\Builder ;

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
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
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
     * @param integer $recordId
     * @param Builder $query
     * @return void
     */
    public function getDependentList(Relatedlist $relatedList, int $recordId, Builder $query)
    {
        // Related field
        $relatedField = $relatedList->relatedField;

        return $query->where($relatedField->column, $recordId);
    }

    /**
     * Counts related records linked by an entity field
     *
     * @param Relatedlist $relatedList
     * @param integer $recordId
     * @return void
     */
    public function getDependentListCount(Relatedlist $relatedList, int $recordId)
    {
        // Related module
        $relatedModule = $relatedList->relatedModule;

        // Model
        $relatedModel = new $relatedModule->model_class;

        // Related field
        $relatedField = $relatedList->relatedField;

        return $relatedModel->where($relatedField->column, $recordId)->count();
    }
}