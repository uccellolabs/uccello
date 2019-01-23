<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;

class Phone extends Text implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType() : string
    {
        return 'tel';
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return 'phone';
    }
}