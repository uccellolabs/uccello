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

    public function placeholder()
    {
        return 'Loading...';
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        // Get module
        $module = ucmodule($this->config['module']);

        // Get record
        $modelClass = $module->model_class;
        $record = $modelClass::find($this->config['record_id']);

        return view('uccello::widgets.relatedlist', [
            'config' => $this->config,
            'domain' => ucdomain($this->config['domain']),
            'module' => $module,
            'data' => (object) $this->config['data'],
            'record' => $record,
            'label' => $this->config['data']->label ?? $this->config['labelForTranslation'],
        ]);
    }
}
