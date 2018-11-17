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

    protected function setTablePrefix()
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
     * @return string
     */
    public function getDeleteLink(Domain $domain, int $sourceRecordId) : string
    {
        return ''; // TODO
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
