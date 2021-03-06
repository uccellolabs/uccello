<div class="form-group">
    <div class="form-line">
        <?php
        $autocompleteSearch = false;

        $entities = [];

        if (isset($column['data']->autocomplete_search) && $column['data']->autocomplete_search === true) {
            $autocompleteSearch = true;
        } else {
            $entities = auth()->user()->getAllowedGroupsAndUsers($domain, false);
        }
        ?>
        <select class="field-search" multiple data-constrain-width="false" data-container=".card-content:parent div" data-alignment="right">
            <option value="me" @if($searchValue && $searchValue=="me")selected="selected"@endif>{{ uctrans('me', $module) }}</option>
            @foreach ($entities as $entity)
            @continue($entity['uuid'] === auth()->user()->uuid)
            <option value="{{ $entity['uuid'] }}" @if($searchValue && in_array($entity['uuid'], (array)$searchValue))selected="selected" @endif>{{ $entity['recordLabel'] }}</option>
            @endforeach
        </select>
    </div>
</div>