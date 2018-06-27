<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Field extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fields';

    /**
     * Fields UI Types
     */
    const UITYPE_TEXT = 'text';
    const UITYPE_EMAIL = 'email';
    const UITYPE_PASSWORD = 'password';
    const UITYPE_HIDDEN = 'hidden';
    const UITYPE_TEXTAREA = 'textarea';
    const UITYPE_NUMBER = 'number';
    const UITYPE_INTEGER = 'integer';
    const UITYPE_FILE = 'file';
    const UITYPE_IMAGE = 'image';
    const UITYPE_URL = 'url';
    const UITYPE_PHONE = 'phone';
    const UITYPE_SEARCH = 'search';
    const UITYPE_COLOR = 'color';
    const UITYPE_DATE = 'date';
    const UITYPE_TIME = 'time';
    const UITYPE_DATETIME = 'datetime';
    const UITYPE_MONTH = 'month';
    const UITYPE_WEEK = 'week';
    const UITYPE_RANGE = 'range';
    const UITYPE_SELECT = 'select';
    const UITYPE_CHOICE = 'choice';
    const UITYPE_CHECKBOX = 'checkbox';
    const UITYPE_BOOLEAN = 'boolean';
    const UITYPE_ENTITY = 'entity';

    /**
     * Fields Display Types
     */
    const DISPLAY_TYPE_EVERYWHERE = "everywhere";
    const DISPLAY_TYPE_CREATE_EDIT_ONLY = "create_edit";
    const DISPLAY_TYPE_DETAIL_ONLY = "detail";
    const DISPLAY_TYPE_CREATE_ONLY = "create";
    const DISPLAY_TYPE_EDIT_ONLY = "edit";
    const DISPLAY_TYPE_HIDDEN = "hidden";
    const DISPLAY_TYPE_INVISIBLE = "invisible";

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    /**
     * Checks if the field can be displayed in List view.
     *
     * @return boolean
     */
    public function isListable() : bool
    {
        return $this->display_type !== self::DISPLAY_TYPE_INVISIBLE;
    }

    /**
     * Checks if the field can be displayed in Detail view.
     *
     * @return boolean
     */
    public function isDetailable() : bool
    {
        return in_array($this->display_type, [self::DISPLAY_TYPE_EVERYWHERE, self::DISPLAY_TYPE_DETAIL_ONLY]);
    }

    /**
     * Checks if the field can be displayed in Edit view (create mode).
     *
     * @return boolean
     */
    public function isCreateable() : bool
    {
        return in_array($this->display_type, [self::DISPLAY_TYPE_EVERYWHERE, self::DISPLAY_TYPE_CREATE_EDIT_ONLY, self::DISPLAY_TYPE_CREATE_ONLY]);
    }

    /**
     * Checks if the field can be displayed in Edit view (edit mode).
     *
     * @return boolean
     */
    public function isEditable() : bool
    {
        return in_array($this->display_type, [self::DISPLAY_TYPE_EVERYWHERE, self::DISPLAY_TYPE_CREATE_EDIT_ONLY, self::DISPLAY_TYPE_EDIT_ONLY]);
    }

    /**
     * Checks if the field can be displayed is hidden.
     * A hidden field can be displayed in List view.
     *
     * @return boolean
     */
    public function isHidden() : bool
    {
        return $this->display_type === self::DISPLAY_TYPE_HIDDEN;
    }

    /**
     * Check if the field can is invisible.
     * An invisible field cannot be displayed anywhere.
     *
     * @return boolean
     */
    public function isInvisible() : bool
    {
        return $this->display_type === self::DISPLAY_TYPE_INVISIBLE;
    }

    /**
     * Returns default icon if defined, else return icon according to uitype.
     *
     * @return string|null
     */
    public function getIconAttribute(): ?string
    {
        if (!empty($this->data) && isset($this->data->icon)) {
            return $this->data->icon;
        }

        $icon = null;

        switch ($this->uitype) {
            // Email
            case self::UITYPE_EMAIL:
                $icon = 'email';
                break;

            // Date and Datetime
            case self::UITYPE_DATE:
            case self::UITYPE_DATETIME:
                $icon = 'date_range';
                break;

            // Time
            case self::UITYPE_TIME:
                $icon = 'access_time';
                break;

            // URL
            case self::UITYPE_URL:
                $icon = 'link';
                break;

            // Image
            case self::UITYPE_IMAGE:
                $icon = 'image';
                break;

            // File
            case self::UITYPE_FILE:
                $icon = 'attachment';
                break;

            // Phone
            case self::UITYPE_PHONE:
                $icon = 'phone';
                break;

            // Password
            case self::UITYPE_PASSWORD:
                $icon = 'lock';
                break;
        }

        return $icon;
    }
}
