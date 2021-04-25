<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Uccello\Core\Models\Module;

class CreateGroupModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createTable();
        $this->createModule();
    }

    private function createTable()
    {
        $prefix = config('uccello.database.table_prefix');
        Schema::create($prefix.'groups', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->string('name');
            $table->foreignId('domain_id')->constrained($prefix.'domains');
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createModule()
    {
        Module::create([
            'name' => 'group',
            'data' => [
                'package' => 'uccello/uccello',
                'model' => \Uccello\Core\Models\Group::class,
                'admin' => true,
                'required' => true,
                'structure' => [
                    'icon' => 'lock',
                    'tabs' => [
                        [
                            'name' => 'main',
                            'blocks' => [
                                [
                                    'name' => 'general',
                                    'icon' => 'info',
                                    'fields' => [
                                        [
                                            'name' => 'name',
                                            'uitype' => 'string',
                                            'displaytype' => 'everywhere',
                                            'required' => true,
                                        ],
                                        [
                                            'name' => 'description',
                                            'uitype' => 'string',
                                            'displaytype' => 'everywhere',
                                        ],
                                    ]
                                ],
                                [
                                    'name' => 'system',
                                    'icon' => 'settings',
                                    'close' => true,
                                    'fields' => [
                                        [
                                            'name' => 'created_at',
                                            'uitype' => 'datetime',
                                            'displaytype' => 'detail',
                                        ],
                                        [
                                            'name' => 'updated_at',
                                            'uitype' => 'datetime',
                                            'displaytype' => 'detail',
                                        ]
                                    ]
                                ]
                            ],
                        ],
                    ],
                    'filters' => [
                        [
                            'name' => 'all',
                            'type' => 'list',
                            'columns' => ['name', 'description'],
                            'default' => true,
                            'readonly' => true,
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('uccello.database.table_prefix').'groups');

        Module::where('name', 'group')->delete();
    }
}
