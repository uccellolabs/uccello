<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Support\Traits\IsExportable;

class ExportController extends Controller
{
    use IsExportable;

    /**
     * Default export format.
     */
    const DEFAULT_EXPORT_FORMAT = 'xlsx';

    /**
     * @inheritDoc
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

        // Initialize Export Manager
        $this->initializeExportManager();

        // Set export options
        $this->setExportOptions();

        // File extension
        $fileExtension = $this->getFileExtension();

        // Download file
        return $this->downloadExportedFile($fileExtension);
    }

    /**
     * Returns file extension defined in the URL param if defined, else returns default one.
     *
     * @return string
     */
    protected function getFileExtension()
    {
        return $this->request->extension ?? static::DEFAULT_EXPORT_FORMAT;
    }

    /**
     * Sets export options according to request params
     *
     * @return void
     */
    protected function setExportOptions()
    {
        // With ID
        $this->setWithIdOption();

        // With timestamps
        $this->setWithTimestampsOption();

        // With descendants
        $this->setWithDescendantsOption();

        // With hidden columns
        $this->setWithColumnsOption();

        // With conditions
        $this->setWithConditionsOption();

        // With order
        $this->setWithOrderOption();
    }

    /**
     * Adds withId option if it was asked.
     *
     * @return void
     */
    protected function setWithIdOption()
    {
        if ($this->request->with_id === '1') {
            $this->withId();
        }
    }

    /**
     * Adds withTimestamp option if it was asked.
     *
     * @return void
     */
    protected function setWithTimestampsOption()
    {
        if ($this->request->with_timestamps === '1') {
            $this->withTimestamps();
        }
    }

    /**
     * Adds withDescendants option if it was asked.
     *
     * @return void
     */
    protected function setWithDescendantsOption()
    {
        if ($this->request->with_descendants === '1') {
            $this->withDescendants();
        }
    }

    /**
     * Adds withColumns option if it was asked.
     *
     * @return void
     */
    protected function setWithColumnsOption()
    {
        if ($this->request->with_hidden_columns !== '1') {
            $columns = json_decode($this->request->columns);
            $this->withColumns($columns);
        }
    }

    /**
     * Adds withConditions option if it was asked.
     *
     * @return void
     */
    protected function setWithConditionsOption()
    {
        if ($this->request->with_conditions === '1') {
            $conditions = json_decode($this->request->conditions);
            $this->withConditions($conditions);
        }
    }

    /**
     * Adds withOrder option if it was asked.
     *
     * @return void
     */
    protected function setWithOrderOption()
    {
        if ($this->request->with_order === '1') {
            $order = json_decode($this->request->order);
            $this->withOrder($order);
        }
    }
}
