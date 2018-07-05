<?php

namespace Uccello\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as DefaultModel;

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
     *
     * @return string
     */
    public function getRecordLabelAttribute() : string
    {
        return $this->id;
    }
}