@if (isset($value))
    {{ $value === true ? uctrans('label.yes', $module) : uctrans('label.no', $module) }}
@endif
