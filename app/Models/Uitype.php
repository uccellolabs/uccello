<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Uitype extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uitypes';

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    /**
     * Returns an instance of the uitype if the related class exists
     *
     * @return mixed
     */
    public function getInstance()
    {
        $modelClass = $this->model_class;

        if (class_exists($modelClass)) {
            return new $modelClass();
        } else {
            return null;
        }
    }

    /**
     * Returns specific uitype view name if exists.
     *
     * @return string|null
     */
    public function getViewName() : ?string
    {
        $viewName = null;

        if (method_exists($this->model_class, 'viewName')) {
            $viewName = $this->getInstance()->viewName();
        }

        return $viewName;
    }
}
