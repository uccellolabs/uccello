<?php

namespace Uccello\Core\Support\Traits;

use Uccello\Core\Exports\RecordsExport;

trait IsExportable
{
    /**
     * Exports data according to user settings and download a file.
     *
     * @param string $fileExtension
     *
     * @return void
     */
    protected function downloadExportedFile($fileExtension)
    {
        $domain = $this->domain;
        $module = $this->module;
        $request = $this->request;

        // File name
        $fileName = uctrans($module->name, $module).'_'.date('Ymd_His');

        // Get writer type according to file extension
        $writerType = $this->getWriterType($fileExtension);

        // Init export
        $export = (new RecordsExport)
                ->forDomain($domain)
                ->forModule($module);

        // With ID
        if ($request->input('with_id') === '1') {
            $export = $export->withId();
        }

        // With timestamps
        if ($request->input('with_timestamps') === '1') {
            $export = $export->withTimestamps();
        }

        // With descendants
        if ($request->input('with_descendants') === '1') {
            $export = $export->withDescendants();
        }

        // With hidden columns
        if ($request->input('with_hidden_columns') !== '1') {
            $columns = json_decode($request->input('columns'));
            $export = $export->withColumns($columns);
        }

        // With conditions
        if ($request->input('with_conditions') === '1') {
            // TODO: Build $conditions['search'] earlier in the export process to match filter format...
            $conditions = ['search' => json_decode($request->input('conditions'))];
            $export = $export->withConditions($conditions);
        }

        // With order
        if ($request->input('with_order') === '1') {
            $order = json_decode($request->input('order'));
            $export = $export->withOrder($order);
        }

        // Export records
        return $export->download($fileName.'.'.$fileExtension, $writerType);
    }

    /**
     * Returns writer type according to file extension
     *
     * @param string $fileExtension
     *
     * @return string
     */
    protected function getWriterType($fileExtension)
    {
        $writerType = null;

        if ($fileExtension === 'pdf') {
            $writerType = \Maatwebsite\Excel\Excel::MPDF;
        }

        return $writerType;
    }
}
