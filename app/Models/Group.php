<?php

namespace Uccello\Core\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Uccello\Core\Database\Eloquent\Model;
use Uccello\Core\Support\Traits\UccelloModule;
use App\Models\User;

class Group extends Model implements Searchable
{
    use SoftDeletes;
    use UccelloModule;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'domain_id'
    ];

    public $searchableType = 'group';

    public $searchableColumns = [
        'name'
    ];

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->recordLabel
        );
    }

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function parentGroups()
    {
        return $this->belongsToMany(static::class, $this->tablePrefix . 'rl_groups_groups', 'children_id', 'parent_id');
    }

    public function childrenGroups()
    {
        return $this->belongsToMany(static::class, $this->tablePrefix . 'rl_groups_groups', 'parent_id', 'children_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, $this->tablePrefix . 'rl_groups_users');
    }

    /**
     * Returns record label
     *
     * @return string
     */
    public function getRecordLabelAttribute() : string
    {
        return $this->name;
    }
}
