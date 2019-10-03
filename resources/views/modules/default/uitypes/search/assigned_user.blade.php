<div class="form-group">
    <div class="form-line">
        <?php
        $autocompleteSearch = false;

        $entities = [];

        if (isset($column['data']->autocomplete_search) && $column['data']->autocomplete_search === true) {
            $autocompleteSearch = true;
        } else {
            $entities = auth()->user()->getAllowedGroupsAndUsers(false);
        }
        ?>
        <select class="field-search" multiple data-constrain-width="false" data-container=".card-content:parent div" data-alignment="right">
            <option value="me">{{ uctrans('me', $module) }}</option>
            @foreach ($entities as $entity)
            @continue($entity['uid'] === auth()->user()->uid)
            <option value="{{ $entity['uid'] }}" @if($searchValue && in_array($entity['uid'], (array)$searchValue))selected="selected" @endif>{{ $entity['recordLabel'] }}</option>
            @endforeach
        </select>
    </div>
</div>