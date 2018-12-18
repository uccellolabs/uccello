<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Relatedlist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'relatedlists';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_id',
        'related_module_id',
        'tab_id',
        'related_field_id',
        'label',
        'icon',
        'type',
        'method',
        'sequence',
        'data',
    ];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function relatedModule()
    {
        return $this->belongsTo(Module::class);
    }

    public function relatedTab()
    {
        return $this->belongsTo(Tab::class);
    }

    public function relatedField()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Returns add link according to related list type
     *
     * @param Domain $domain
     * @param integer $sourceRecordId
     * @return string
     */
    public function getAddLink(Domain $domain, int $sourceRecordId) : string
    {
        // Default parameters
        $params = [
            'relatedlist' => $this->id,
            'src_id' => $sourceRecordId
        ];

        // If it is a N-1 related list, add value of the linked field
        if ($this->type === 'n-1') {
            $params[$this->relatedField->name] = $sourceRecordId;
        }

        // Add tab id if defined
        if ($this->tab_id) {
            $params['tab'] = $this->tab_id;
        }

        return ucroute('uccello.edit', $domain, $this->relatedModule, $params);
    }

    /**
     * Returns edit link according to related list type
     *
     * @param Domain $domain
     * @param integer $sourceRecordId
     * @return string
     */
    public function getEditLink(Domain $domain, int $sourceRecordId) : string
    {
        // Default parameters
        $params = [
            'id' => 'RECORD_ID', // RECORD_ID will be replaced automaticaly by the record id in the datatable
            'relatedlist' => $this->id,
            'src_id' => $sourceRecordId
        ];

        // Add tab id if defined
        if ($this->tab_id) {
            $params['tab'] = $this->tab_id;
        }

        return ucroute('uccello.edit', $domain, $this->relatedModule, $params);
    }

    /**
     * Returns delete link according to related list type
     *
     * @param Domain $domain
     * @param integer $sourceRecordId
     * @param boolean $deleteRelation
     * @return string
     */
    public function getDeleteLink(Domain $domain, int $sourceRecordId, bool $preferDeleteRelation = true) : string
    {
        // Default parameters
        $params = [
            'id' => 'RECORD_ID', // RECORD_ID will be replaced automaticaly by the record id in the datatable
            'relatedlist' => $this->id,
            'src_id' => $sourceRecordId
        ];

        // Add tab id if defined
        if ($this->tab_id) {
            $params['tab'] = $this->tab_id;
        }

        // If it is a N-N related list and if necessary add the relation id. It will delete the relation instead of the record
        if ($this->type === 'n-n' && $preferDeleteRelation === true) {
            $params['relation_id'] = 'RELATION_ID'; // RELATION_ID will be replaced automaticaly by the relation id in the datatable
        }

        return ucroute('uccello.delete', $domain, $this->relatedModule, $params);
    }

    /**
     * Checks if it is possible to add a record.
     *
     * @return boolean
     */
    public function canAdd() : bool
    {
        return isset($this->data->actions) && in_array('add', $this->data->actions);
    }

    /**
     * Checks if it is possible to select a record.
     *
     * @return boolean
     */
    public function canSelect() : bool
    {
        return $this->type === 'n-n' && isset($this->data->actions) && in_array('select', $this->data->actions);
    }
}
