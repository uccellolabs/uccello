<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Uccello\Core\Models\Module;

class CreateUserModule extends Migration
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
        Schema::table('users', function(Blueprint $table) {
            $table->string('username')->unique()->after('id');
            $table->boolean('is_admin')->after('remember_token')->default(false);
            $table->foreignId('domain_id')->after('is_admin')->constrained(config('uccello.database.table_prefix').'domains');
            $table->json('data')->after('domain_id')->nullable();
            $table->softDeletes();
        });
    }

    private function createModule()
    {
        Module::create([
            'name' => 'user',
            'data' => [
                'package' => 'uccello/uccello',
                'model' => \App\Models\User::class,
                'admin' => true,
                'required' => true,
                'structure' => [
                    'icon' => 'person',
                    'tabs' => [
                        [
                            'name' => 'main',
                            'blocks' => [
                                [
                                    'name' => 'general',
                                    'icon' => 'info',
                                    'fields' => [
                                        [
                                            'name' => 'username',
                                            'uitype' => 'string',
                                            'displaytype' => 'everywhere',
                                            'required' => true,
                                            'rules' => [
                                                'regex:/^(?:[A-Z\d][A-Z\d_-]{3,}|[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})$/i',
                                                'unique:users,username,%id%',
                                            ],
                                        ],
                                        [
                                            'name' => 'is_admin',
                                            'uitype' => 'boolean',
                                            'displaytype' => 'everywhere',
                                        ],
                                        [
                                            'name' => 'name',
                                            'uitype' => 'string',
                                            'displaytype' => 'everywhere',
                                            'required' => true,
                                            'icon' => 'person',
                                        ],
                                        [
                                            'name' => 'email',
                                            'uitype' => 'email',
                                            'displaytype' => 'everywhere',
                                            'required' => true,
                                            'rules' => [
                                                'email',
                                                'unique:users,email,%id%',
                                            ],
                                        ],
                                        [
                                            'name' => 'password',
                                            'uitype' => 'password',
                                            'displaytype' => 'create',
                                            'required' => true,
                                            'repeated' => true,
                                            'rules' => [
                                                'min:6'
                                            ],
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
                            'columns' => ['username', 'name', 'email', 'is_admin'],
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
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('is_admin');
            $table->dropColumn('domain_id');
        });

        Module::where('name', 'user')->delete();
    }
}
