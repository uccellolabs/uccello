<?php

namespace Uccello\Core\Support\Structure;

class Tab
{
    public $name;
    public $icon;
    public $blocks = [];

    /**
     * Constructure
     *
     * @param \stdclass|array|null $data
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

    /**
     * Adds a new block.
     * Initialize blocks collection if necessary.
     * Convert stdClass to Block if necessary.
     *
     * @param \stdClass|array|\Uccello\Core\Support\Structure\Block $block
     *
     * @return \Uccello\Core\Support\Structure\Block
     */
    public function addBlock($block)
    {
        // Initialize blocks
        if (empty($this->blocks)) {
            $this->blocks = collect();
        }

        // Convert block if necessary
        if ($block instanceof Block === false) {
            $block = new Block($block);
        }

        // Add block
        $this->blocks[] = $block;

        return $block;
    }

    /**
     * Checks if tab is visible in a view.
     * A block is visible if at least one block is visible.
     *
     * @param string $viewName
     *
     * @return boolean
     */
    public function isVisible(string $viewName)
    {
        $isVisible = false;

        if ($this->blocks) {
            foreach ($this->blocks as $block) {
                if ($block->isVisible($viewName)) {
                    $isVisible = true;
                    break;
                }
            }
        }

        return $isVisible;
    }
}
