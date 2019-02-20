<?php

namespace Uccello\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as DefaultModel;
use Uccello\Core\Support\Traits\RelatedlistTrait;

class Model extends DefaultModel
{
    use RelatedlistTrait;

    protected $tablePrefix;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'recordLabel'
    ];

    public function __construct(array $attributes = [ ])
    {
        parent::__construct($attributes);

        // Init table prefix
        $this->initTablePrefix();

        // Init table name
        $this->initTableName();
    }

    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    protected function initTablePrefix()
    {
        $this->tablePrefix = '';
    }

    protected function initTableName()
    {
        if ($this->table)
        {
            $this->table = $this->tablePrefix.$this->table;
        }
    }
}