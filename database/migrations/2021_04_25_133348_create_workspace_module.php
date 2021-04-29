<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Uccello\Core\Models\Module;

class CreateWorkspaceModule extends Migration
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
        Schema::create(config('uccello.database.table_prefix').'workspaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('parent_id')->nullable()->constrained($table->getTable());
            $table->string('path', 255)->nullable();
            $table->integer('level')->default(0);
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['path', 'parent_id', 'level']);
        });
    }

    private function createModule()
    {
        Module::create([
            'name' => 'workspace',
            'data' => [
                'name' => 'workspace',
                'package' => 'uccello/uccello',
                'model' => \Uccello\Core\Models\Workspace::class,
                'admin' => true,
                'required' => true,
                'structure' => [
                    'icon' => 'workspace',
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
                                            'type' => 'string',
                                            'visible' => true,
                                            'required' => true,
                                            'rules' => [
                                                'regex:/(?!^\d+$)^.+$/',
                                                'unique:'.config('uccello.database.table_prefix').'workspaces,name,%id%'
                                            ]
                                        ],
                                        [
                                            'name' => 'parent',
                                            'type' => 'entity',
                                            'visible' => true,
                                            'config' => [
                                                'module' => 'workspace',
                                            ]
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
                                            'type' => 'datetime',
                                            'visible' => [
                                                'detail' => true,
                                                'list' => true
                                            ],
                                        ],
                                        [
                                            'name' => 'updated_at',
                                            'type' => 'datetime',
                                            'visible' => [
                                                'detail' => true,
                                                'list' => true
                                            ],
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
        Schema::dropIfExists(config('uccello.database.table_prefix').'workspaces');

        Module::where('name', 'workspace')->delete();
    }
}
