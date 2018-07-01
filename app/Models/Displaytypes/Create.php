<?php

namespace Uccello\Core\Models\Displaytypes;

use Uccello\Core\Contracts\Field\Displaytype;

class Create implements Displaytype
{
    /**
     * Checks if the field can be displayed in List view.
     *
     * @return boolean
     */
    public function isListable() : bool
    {
        return false;
    }

    /**
     * Checks if the field can be displayed in Detail view.
     *
     * @return boolean
     */
    public function isDetailable() : bool
    {
        return false;
    }

    /**
     * Checks if the field can be displayed in Edit view (create mode).
     *
     * @return boolean
     */
    public function isCreateable() : bool
    {
        return true;
    }

    /**
     * Checks if the field can be displayed in Edit view (edit mode).
     *
     * @return boolean
     */
    public function isEditable() : bool
    {
        return false;
    }
}