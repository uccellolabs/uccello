<?php
namespace Uccello\Core\Http\Controllers\Core;

use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;
use Uccello\Core\Forms\PopupEditForm;
use Uccello\Core\Events\BeforeSaveEvent;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Relatedlist;

class PopupEditController extends Controller
{
    protected $viewName = 'edit.popup';
    protected $formBuilder;
    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:create');
    }
    public function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
        parent::__construct();
    }
    /**
     * {@inheritdoc}
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);
        // Retrieve record or get a new empty instance
        $record = $this->getRecordFromRequest();
        // Get form
        $form = $this->getForm($domain, $module, $request);
        // Get mode
        $mode = 'create';
        return $this->autoView([
            'form' => $form,
            'record' => $record,
            'mode' => $mode
        ]);
    }
    /**
     * Create or update record into database
     *
     * @param Domain|null $domain
     * @param Module $module
     * @param boolean $redirect
     * @return void
     */
    public function save(?Domain $domain, Module $module, Request $request, bool $redirect = false)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);
        // Retrieve record or get a new empty instance
        $record = $this->getRecordFromRequest();
        // Get form
        $form = $this->getForm($domain, $module, $request, $record);
        $values = $form->getFieldValues();
        $record = $form->getModel();
        $request = $form->getRequest();
        //Add fields value to record
        foreach ($values as $fieldName => $value) {
            $field = $module->getField($fieldName);
            // If the field exists format the value and store it in the good model column
            if (!is_null($field)) {
                $column = $field->column;
                $form->getModel()->$column = uitype($field->uitype_id)->getFormattedValueToSave($request, $field, $value, $record, $domain, $module);
            }
        }
        // Redirect if form not valid
        if (! $form->isValid()) {
            ucnotify(uctrans('notification.form.not_valid', $module), 'error');
            $response = redirect(route('uccello.popup.edit', ['domain' => $domain->slug, 'module' => $module->name]));
            $response = $response->withErrors($form->getErrors(), $form->getErrorBag())->withInput();
            return $response;
        }
        event(new BeforeSaveEvent($domain, $module, $request, $record, 'create'));
        // Save record
        $record->save();
        event(new AfterSaveEvent($domain, $module, $request, $record, 'create'));
        return $record;
    }
    public function getForm($domain, $module, $request, $record = null)
    {
        if ($record === null) {
            $modelClass = $module->model_class;
            $record = new $modelClass;
        }
        return $this->formBuilder->create(PopupEditForm::class, [
            'model' => $record,
            'data' => [
                'domain' => $domain,
                'module' => $module,
                'request' => $request
            ]
        ]);
    }
}
