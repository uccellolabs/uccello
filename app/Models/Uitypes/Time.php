<?php

namespace Uccello\Core\Models\Uitypes;

use Uccello\Core\Contracts\Field\Uitype;

class Time extends DateTime implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType(): string
    {
        return 'time';
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return 'access_time';
    }
}