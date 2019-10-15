<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Filter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'filters';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'columns' => 'object',
        'conditions' => 'object',
        'order' => 'object',
        'data' => 'object',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_id',
        'domain_id',
        'user_id',
        'name',
        'type',
        'columns',
        'conditions',
        'order',
        'is_default',
        'is_public',
        'data'
    ];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Check if the filter is for read only
     *
     * @return boolean
     */
    public function getReadOnlyAttribute()
    {
        return $this->data->readonly ?? false;
    }

    /**
     * Refactor data from columns:{}
     *
     * @param array $data
     * @return Filter
     */
    public static function newFromData($data)
    {
        $filter = null;

        if (is_array($data)) {
            $filter = new static();

            // Refactor $data to mach match 'conditions' filter format...
            if(!empty($data['columns']) && is_array($data['columns']) && is_array(reset($data['columns'])))
            {
                if (empty($data['conditions'])) {
                    $data['conditions'] = [];
                }
                else {
                    $data['conditions'] = (array) $data['conditions'];
                }

                foreach ($data['columns'] as $fieldName => $field) {
                    foreach ($field as $key => $value) {
                        if (!empty($value) && $key != 'columnName') {
                            $data['conditions'][$key][$fieldName] = $value;
                        }
                    }
                }

                unset($data['columns']);
            }

            foreach ($data as $key => $value) {
                $filter->{$key} = $value;
            }
        }

        return $filter;
    }
}
