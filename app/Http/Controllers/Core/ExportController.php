<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Exports\RecordsExport;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Support\Traits\IsExportable;

class ExportController extends Controller
{
    use IsExportable;

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:retrieve');
    }

    /**
     * @inheritDoc
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // File extension
        $fileExtension = $request->input('extension') ?? 'xlsx';

        // Download file
        return $this->downloadExportedFile($fileExtension);
    }
}
