<?php

namespace Uccello\Core\Console\Commands;

use Illuminate\Console\Command;
use Uccello\Core\Models\Entity;
use Uccello\Core\Models\Module;

class GenerateUuidCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uccello:uuid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge uuids linked to deleted records and generate cache for uuid';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->purgeDeletedRecords();
        $this->generateCache();
    }

    protected function purgeDeletedRecords()
    {
        $modules = Module::whereNotNull('model_class')->get();
        foreach ($modules as $module) {
            $this->info('Purging '.$module->name);

            $modelClass = $module->model_class;

            $query = Entity::where('module_id', $module->id)
                ->whereNotIn('record_id', function ($query) use ($modelClass) {
                    $query->select('id')
                        ->from((new $modelClass)->getTable())
                        ->get();
                });

            $this->comment('Total to delete: '.$query->count());

            $query->delete();
        }
    }

    protected function generateCache()
    {
        $modules = Module::whereNotNull('model_class')->get();
        foreach ($modules as $module) {
            $this->info('Generating cache for '.$module->name);

            $modelClass = $module->model_class;
            $count = $modelClass::count();
            $cpt = 0;
            $modelClass::chunkById(300, function ($records) use (&$cpt, $count) {
                $this->comment($cpt.'/'.$count);
                foreach ($records as $record) {
                    $record->uuid; // Automaticaly generate cache (see \Uccello\Core\Support\Traits\UccelloModule getUuidAttribute())
                }
                $cpt += 300;
            });
        }
    }
}
