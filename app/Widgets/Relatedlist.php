<?php

namespace Uccello\Core\Widgets;

use Arrilot\Widgets\AbstractWidget;

class Relatedlist extends AbstractWidget
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

        return view('uccello::widgets.relatedlist', [
            'config' => $this->config,
        ]);
    }
}
