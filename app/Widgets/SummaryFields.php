<?php

namespace Uccello\Core\Widgets;

use Arrilot\Widgets\AbstractWidget;

class SummaryFields extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [ ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view('uccello::widgets.summary_fields', [
            'config' => $this->config,
        ]);
    }
}
