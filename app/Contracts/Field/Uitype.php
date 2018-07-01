<?php

namespace Uccello\Core\Contracts\Field;

use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

interface Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @param Field $field
     * @return string
     */
    public function getFormType(): string;

    /**
     * Returns options for Form builder.
     *
     * @param Field $field
     * @param Module $module
     * @return array
     */
    public function getFormOptions(Field $field, Module $module) : array;

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string;
}