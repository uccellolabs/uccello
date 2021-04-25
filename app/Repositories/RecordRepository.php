<?php

namespace Uccello\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use Uccello\Core\Models\Module;

class RecordRepository
{
    protected $model;

    public function __construct(Module $module)
    {
        $modelClass = $module->data->model ?? null;
        $this->model = !empty($modelClass) ? new $modelClass : null;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getByIds($ids)
    {
        if ($ids == null) {
            return [];
        }

        if (is_array($ids)) {
            return $this->model->whereIn('id', $ids)->get();
        }

        throw new \InvalidArgumentException("Argument must be an array of indexes to get");
    }

    public function getCount()
    {
        return $this->model->count();
    }

    /**
     * @return Model
     */
    public function newInstance()
    {
        return $this->model->newInstance();
    }

    public function create($data)
    {
        if (is_array($data)) {
            return $this->model->create($data);
        }

        if (get_class($data) == get_class($this->model)) {
            return $data->save();
        }

        throw new \InvalidArgumentException("Argument must be an array or with type ".get_class($this->model));
    }

    public function update($data, $id = 0, $attribute = "id")
    {
        if ($data instanceof Model) {
            return $data->save();
        }
        if (is_array($data)) {
            return $this->model->where($attribute, '=', $id)->update($data);
        }

        throw new \InvalidArgumentException("Arguments must be a model or an array with an ID");
    }

    public function delete($ids)
    {
        return $this->model->delete($ids);
    }
}
