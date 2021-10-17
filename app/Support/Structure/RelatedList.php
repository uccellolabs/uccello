<?php

namespace Uccello\Core\Support\Structure;

class RelatedList
{
    public $name;
    public $module;
    public $relatedModule;

    /**
     * Constructure
     *
     * @param \stdClass|array|null $data
     */
    public function __construct($data = null)
    {
        if ($data === null || is_object($data) || is_array($data)) {
            // Convert to stdClass if necessary
            if (is_array($data)) {
                $data = json_decode(json_encode($data));
            }

            // Set data
            foreach ($data as $key => $val) {
                $this->{$key} = $val;
            }
        } else {
            throw new \Exception('First argument must be an object');
        }
    }
}
