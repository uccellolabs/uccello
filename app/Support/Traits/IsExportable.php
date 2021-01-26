<?php

namespace Uccello\Core\Support\Traits;

use Uccello\Core\Exports\ExportManager;

trait IsExportable
{
    /**
     * Export Manager
     *
     * @var \Uccello\Core\Exports\ExportManager
     */
    protected $exportManager;

    /**
     * Exports data according to user settings and download a file.
     *
     * @param string $fileExtension
     *
     * @return void
     */
    protected function downloadExportedFile($fileExtension)
    {
        // Abort if the export manager is not initialized
        $this->abortIfExportManagerIsNotInitialized();

        // Get downloaded file name
        $downloadedFileName = $this->getDownloadedFileName($fileExtension);

        // Get writer type according to file extension
        $writerType = $this->getWriterType($fileExtension);

        // Export records
        return $this->exportManager->download($downloadedFileName, $writerType);
    }

    /**
     * Constructs and returns file name.
     * It is build with the translated name of the module concatenated with the current date and file extension.
     *
     * @param string $fileExtension
     *
     * @return string
     */
    protected function getDownloadedFileName($fileExtension)
    {
        $fileName = uctrans($this->module->name, $this->module).'_'.date('Ymd_His');
        $fileNameWithExtension = "$fileName.$fileExtension";

        return $fileNameWithExtension;
    }

    /**
     * Returns a 500 error if the Export Manager was not initialized.
     *
     * @return void
     */
    protected function abortIfExportManagerIsNotInitialized()
    {
        if (!$this->exportManager) {
            abort('500', 'Export manager is not defined. You have to use initializeExportManager() to do this.');
        }
    }

    /**
     * Initializes the Export Manager.
     *
     * @return void
     */
    protected function initializeExportManager()
    {
        $this->exportManager = (new ExportManager)
                ->forDomain($this->domain)
                ->forModule($this->module);
    }

    /**
     * Informs the Export Manager that it must export records ids.
     *
     * @return void
     */
    protected function withId()
    {
        $this->exportManager->withId();

        return $this;
    }

    /**
     * Informs the Export Manager that it must export created_at and updated_at columns.
     *
     * @return void
     */
    protected function withTimestamps()
    {
        $this->exportManager->withTimestamps();

        return $this;
    }

    /**
     * Informs the Export Manager that it must export also records from descendants domain.
     * It will be possible only if the user is allowed to do this.
     *
     * @return void
     */
    protected function withDescendants()
    {
        $this->exportManager->withDescendants();

        return $this;
    }

    /**
     * Informs the Export Manager that it must export only some columns.
     *
     * @return void
     */
    protected function withColumns($columns)
    {
        $this->exportManager->withColumns($columns);

        return $this;
    }

    /**
     * Informs the Export Manager that it must export records according to some conditions.
     *
     * @return void
     */
    protected function withConditions($conditions)
    {
        $this->exportManager->withConditions($conditions);

        return $this;
    }

    /**
     * Informs the Export Manager that it must export records respecting sort order.
     *
     * @return void
     */
    protected function withOrder($order)
    {
        $this->exportManager->withOrder($order);

        return $this;
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
            $writerType = \Maatwebsite\Excel\Excel::MPDF; // Or DOMPDF, or TCPDF
        }

        return $writerType;
    }
}
