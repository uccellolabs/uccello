<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Storage;


class DownloadController extends Controller
{
    protected $viewName = null;

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:retrieve');
    }

    /**
     * Process and display asked page
     * @param Domain|null $domain
     * @param Module $module
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get file data from request
        $fileData = $this->getFileDataFromRequest();
        $fileName = $fileData['name'];
        $filePath = $fileData['path'];

        // Check if file exists
        if (!Storage::exists($filePath)) {
            abort(404);
        }

        // Download file
        return Storage::download($filePath, $fileName);
    }

    /**
     * Get file name and path from request data
     *
     * @return array|\Illuminate\Http\Response
     */
    protected function getFileDataFromRequest()
    {
        $fileName = null;
        $filePath = null;

        $recordId = (int) request('id');
        $fieldColumn = request('field');

        // Get record
        $record = $this->getRecord($recordId);

        // Retrieve data from field column
        $fileData = $record->{$fieldColumn};

        if ($fileData) {
            $fileParts = explode(';', $fileData);

            if (count($fileParts) === 2) {
                $fileName = $fileParts[0];
                $filePath = $fileParts[1];
            }
        }

        if (!$fileName || !$filePath) {
            abort(404);
        }

        return [
            'name' => $fileName,
            'path' => $filePath
        ];
    }

    /**
     * Get record by id
     *
     * @param int $id
     * @return void
     */
    protected function getRecord(int $id)
    {
        $record = null;

        $modelClass = $this->module->model_class;
        $record = $modelClass::findOrFail($id);

        return $record;
    }
}
