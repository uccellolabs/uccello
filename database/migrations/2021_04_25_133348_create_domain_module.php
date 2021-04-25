<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Uccello\Core\Models\Module;

class CreateDomainModule extends Migration
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
        Schema::create(config('uccello.database.table_prefix').'domains', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createModule()
    {
        Module::create([
            'name' => 'domain',
            'data' => [
                'package' => 'uccello/uccello',
                'model' => \Uccello\Core\Models\Domain::class,
                'admin' => true,
                'required' => true,
                'structure' => [
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
                                            'rules' => [
                                                'regex:/(?!^\d+$)^.+$/|unique:'.config('uccello.database.table_prefix').'domains,name,%id%'
                                            ]
                                        ],
                                        [
                                            'name' => 'parent',
                                            'uitype' => [
                                                'name' => 'entity',
                                                'module' => 'domain',
                                            ],
                                            'displaytype' => 'everywhere',
                                        ]
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
                            'columns' => ['name', 'parent'],
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
        Schema::dropIfExists(config('uccello.database.table_prefix').'domains');
    }
}
