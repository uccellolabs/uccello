<?php $isLarge = $field->data->large ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-md-6' }}">
    Value    
</div>

{{-- if it is a repeated field display only the second one --}}
@if($field->data->repeated ?? false)
<div class="{{ $isLarge ? 'col-md-12' : 'col-md-6' }}">
    Value
</div>
@endif