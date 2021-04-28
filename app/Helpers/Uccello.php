<?php

namespace Uccello\Core\Helpers;

class Uccello
{
    /**
     * Returns true if multi workspaces option is activated, false else.
     *
     * @return boolean
     */
    public function useMultiWorkspaces()
    {
        return config('uccello.workspace.multi_workspaces');
    }
}
