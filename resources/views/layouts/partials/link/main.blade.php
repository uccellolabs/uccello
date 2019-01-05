<?php
    // CSS class
    if (!empty($link->data->class)) {
        // Custom CSS
        $cssClass = $link->data->class;
    } else {
        // Default CSS
        $cssClass = 'waves-effect waves-block ';

        // 'detail.action' displays a link. Other type displays a button
        if ($link->type !== 'detail.action') {
            // Button
            $cssClass .= 'btn ';

            // Icon
            if ($link->icon) {
                $cssClass .= 'icon-right ';
            }

            // Color
            if (!empty($link->data->color)) {
                $cssClass .= 'bg-'.$link->data->color.' ';
            } else {
                $cssClass .= 'bg-primary ';
            }
        }
    }

    // Link URL
    $linkUrl = $link->url;

    // Replace variables in the link
    if (isset($record)) {
        // Get all variables from the URL (e.g. http://domain.tld?id=$id$)
        preg_match_all('`\$(.+?)\$`', $linkUrl, $matches);

        foreach ($matches[ 1 ] as $i => $attribute) {
            // Get
            $value = ucattribute($record, $attribute);

            // Replace the variable by its value
            $linkUrl = str_replace($matches[ 0 ][ $i ], urlencode($value), $linkUrl);
        }
    }
?>
<a href="{{ $linkUrl }}"
    class="{{ $cssClass }}"
    @if ($link->data->target ?? false)target="{{ $link->data->target }}"@endif
    data-config='{!! json_encode($link->data) !!}'
>
    @if ($link->icon)
    <i class="material-icons">{{ $link->icon }}</i>
    @endif
    {{ uctrans($link->label, $module) }}
</a>