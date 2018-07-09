<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Support\Facades\Hash;
use Uccello\Core\Contracts\Field\Uitype;

use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Domain;

class Password extends Text implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType(): string
    {
        return 'password';
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return 'lock';
    }

    /**
     * Returns formatted value to save.
     *
     * @param Field $field
     * @param mixed|null $value
     * @param mixed|null $record
     * @param Domain|null $domain
     * @param Module|null $module
     * @return string|null
     */
    public function getFormattedValueToSave(Field $field, $value, $record=null, ?Domain $domain=null, ?Module $module=null) : ?string
    {
        return Hash::make($value);
    }
}