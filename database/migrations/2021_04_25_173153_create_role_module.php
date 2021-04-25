<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Uccello\Core\Models\Module;

class CreateRoleModule extends Migration
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
        Schema::create($prefix.'roles', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained($table->getTable());
            $table->string('path', 255)->nullable();
            $table->integer('level')->default(0);
            $table->foreignId('domain_id')->constrained($prefix.'domains');
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createModule()
    {
        Module::create([
            'name' => 'role',
            'data' => [
                'package' => 'uccello/uccello',
                'model' => \Uccello\Core\Models\Role::class,
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
                                            'name' => 'parent',
                                            'uitype' => [
                                                'name' => 'entity',
                                                'module' => 'role',
                                            ],
                                            'displaytype' => 'everywhere',
                                        ],
                                        [
                                            'name' => 'see_descendant_records',
                                            'uitype' => 'boolean',
                                            'displaytype' => 'everywhere',
                                            'info' => 'field_info.see_descendant_records',
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
                            'columns' => ['name', 'parent', 'description', 'see_descendant_records'],
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
        Schema::dropIfExists(config('uccello.database.table_prefix').'roles');

        Module::where('name', 'role')->delete();
    }
}
