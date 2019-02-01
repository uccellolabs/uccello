<?php

namespace Uccello\Core\Listeners\Core;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Uccello\Core\Events\BeforeSaveEvent;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Field;

class AfterSaveEventListener
{
    protected $auth;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Activate mandatory modules on domain.
     *
     * @param  AfterSaveEvent  $event
     * @return void
     */
    public function handle(AfterSaveEvent $event)
    {
        $domain = $event->domain;
        $module = $event->module;

        foreach ($module->fields()->get() as $field) {
            // If it is uitype reference, update reference record
            if ($field->uitype->id === uitype('reference')->id) {
                $this->updateReferenceRecord($event, $field);
            }
        }
    }

    /**
     * Update reference record if exists
     *
     * @param AfterSaveEvent $event
     * @param Field $field
     * @return void
     */
    protected function updateReferenceRecord(AfterSaveEvent $event, Field $field)
    {
        $module = $event->module;
        $domain = $event->domain;
        $record = $event->record;
        $request = $event->request;
        $user = $this->auth->user();

        $uitypeClass = $field->uitype->class;
        $uitype = new $uitypeClass();

        $referenceField = $uitype->getReferenceField($field);
        $referenceModule = $uitype->getReferenceModule($field);
        $referenceRecord = $uitype->getReferenceRecord($field, $record);

        // Check if the user can update the reference module
        if (!$user->canUpdate($domain, $referenceModule)) {
            return;
        }

        if (!is_null($referenceField) && !is_null($referenceModule) && !is_null($referenceRecord)) {
            $value = request($field->name);

            event(new BeforeSaveEvent($domain, $module, $request, $record, 'edit'));

            // Update reference record
            $referenceRecord->{$referenceField->column} = $referenceField->uitype->getFormattedValueToSave($request, $field, $value, $referenceRecord, $domain, $referenceModule);
            $referenceRecord->save();

            event(new AfterSaveEvent($domain, $referenceModule, $request, $referenceRecord, 'edit'));
        }
    }
}
