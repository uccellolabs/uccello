<?php

namespace Uccello\Core\Support\Structure;

class Tab
{
    private $data;

    /**
     * Constructure
     *
     * @param \stdclass $data
     */
    public function __construct($data = null)
    {
        if ($data === null || is_object($data)) {
            // Set data
            $this->data = $data;

            // Convert structure
            $this->convertStructure();
        } else {
            throw new \Exception('First argument must be an object');
        }
    }

    /**
     * Getter to retrieve an attribute from $data.
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function __get(string $attribute)
    {
        return optional($this->data)->{$attribute};
    }

    /**
     * Setter to update an attribute into $data.
     *
     * @param string $attribute
     * @param mixed $value
     */
    public function __set(string $attribute, $value)
    {
        $this->data->{$attribute} = $value;

        if ($attribute === 'blocks') {
            $this->convertBlocks();
        }
    }

    /**
     * Convert structure.
     * All structure object will be converted into specialized structure object.
     *
     * @return void
     */
    private function convertStructure()
    {
        // Blocks
        $this->convertBlocks();
    }

    /**
     * Convert blocks.
     *
     * @return void
     */
    private function convertBlocks()
    {
        if (!empty($this->data->blocks)) {
            $blocks = collect();
            foreach ($this->data->blocks as $block) {
                $blocks[] = new Block($block);
            }
            $this->data->blocks = $blocks;
        }
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

        if (optional($this->data)->blocks) {
            foreach ($this->data->blocks as $block) {
                if ($block->isVisible($viewName)) {
                    $isVisible = true;
                    break;
                }
            }
        }

        return $isVisible;
    }
}
