<?php

namespace Uccello\Core\Http\Controllers\User;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\DetailController as CoreDetailController;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class DetailController extends CoreDetailController
{
    /**
     * {@inheritdoc}
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Get default view
        $view = parent::process($domain, $module, $request);

        // Useful if multi domains is not used
        $domain = $this->domain;

        // Get record
        $record = $this->getRecordFromRequest();

        // Get last connections
        $connections = [ ];
        if ($record) {
            $connections = $record->connections()
                ->orderBy('datetime', 'desc')
                ->take(config('uccello.users.max_connections_displayed'))
                ->get();
        }

        // Get connections count
        $connectionsCount = $record->connections()->count();

        // Get first connection
        $firstConnection = $record->connections()
            ->orderBy('datetime', 'asc')
            ->first();

        // Add data to the view
        $view->connections = $connections;
        $view->connectionsCount = $connectionsCount;
        $view->firstConnection = $firstConnection;

        return $view;
    }
}
