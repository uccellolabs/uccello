<?php

namespace Uccello\Core\Helpers;

class Uccello
{
    /**
     * Returns true if multi domains option is activated, false else.
     *
     * @return boolean
     */
    public function useMultiDomains()
    {
        return config('uccello.domain.multi_domains');
    }
}
