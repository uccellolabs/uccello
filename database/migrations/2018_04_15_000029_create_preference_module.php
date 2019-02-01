<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Tab;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Link;

class CreatePreferenceModule extends Migration
{
    use TablePrefixTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createTable();
        $module = $this->createModule();
        $this->activateModuleOnDomains($module);
        $this->createTabsBlocksFields($module);
        $this->createLinks($module);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop table
        Schema::dropIfExists($this->tablePrefix . 'preferences');

        // Delete module
        Module::where('name', 'preference')->forceDelete();
    }

    protected function createTable()
    {
        Schema::create($this->tablePrefix . 'preferences', function (Blueprint $table) {
            $table->unsignedInteger('id')->unique();
            $table->string('decimals');
            $table->string('decimals_separator');
            $table->string('thousands_separator');
            $table->string('date_format');
            $table->timestamps();

            $table->foreign('id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    protected function createModule()
    {
        return Module::create([
            'name' => 'preference',
            'icon' => 'build',
            'model_class' => 'Uccello\Core\Models\Preference',
            'data' => [ "package" => "uccello\/uccello", "mandatory" => true, "menu" => false, "save_new" => false ]
        ]);
    }

    protected function activateModuleOnDomains($module)
    {
        $domains = Domain::all();
        foreach ($domains as $domain) {
            $domain->modules()->attach($module);
        }
    }

    protected function createTabsBlocksFields($module)
    {
        // Main tab
        $tab = Tab::create([
            'label' => 'tab.main',
            'icon' => null,
            'sequence' => 0,
            'module_id' => $module->id,
        ]);

        // General block
        $block = Block::create([
            'label' => 'block.general',
            'icon' => 'info',
            'data' => null,
            'sequence' => 0,
            'tab_id' => $tab->id,
            'module_id' => $module->id,
        ]);

        // Username
        Field::create([
            'name' => 'username',
            'uitype_id' => uitype('reference')->id,
            'displaytype_id' => displaytype('everywhere')->id,
            'data' => [ 'rules' => 'required|regex:/^[a-zA-Z0-9.-_]+$/|unique:users,username,auth:id', 'reference' => 'user,username,auth:id' ],
            'sequence' => 0,
            'block_id' => $block->id,
            'module_id' => $module->id,
        ]);

        // Email
        Field::create([
            'name' => 'email',
            'uitype_id' => uitype('reference')->id,
            'displaytype_id' => displaytype('everywhere')->id,
            'data' => [ 'rules' => 'required|email|unique:users,email,auth:id', 'reference' => 'user,email,auth:id' ],
            'sequence' => 1,
            'block_id' => $block->id,
            'module_id' => $module->id,
        ]);

        // First name
        Field::create([
            'name' => 'first_name',
            'uitype_id' => uitype('reference')->id,
            'displaytype_id' => displaytype('everywhere')->id,
            'data' => [ 'reference' => 'user,first_name,auth:id' ],
            'sequence' => 2,
            'block_id' => $block->id,
            'module_id' => $module->id,
        ]);

        // Last name
        Field::create([
            'name' => 'last_name',
            'uitype_id' => uitype('reference')->id,
            'displaytype_id' => displaytype('everywhere')->id,
            'data' => [ 'rules' => 'required', 'reference' => 'user,last_name,auth:id' ],
            'sequence' => 3,
            'block_id' => $block->id,
            'module_id' => $module->id,
        ]);

        // Phone
        Field::create([
            'name' => 'phone',
            'uitype_id' => uitype('reference')->id,
            'displaytype_id' => displaytype('everywhere')->id,
            'data' => [ 'reference' => 'user,phone,auth:id' ],
            'sequence' => 4,
            'block_id' => $block->id,
            'module_id' => $module->id,
        ]);

        // Format block
        $block = Block::create([
            'label' => 'block.format',
            'icon' => 'format_paint',
            'data' => null,
            'sequence' => 1,
            'tab_id' => $tab->id,
            'module_id' => $module->id,
        ]);

        // Decimals
        Field::create([
            'name' => 'decimals',
            'uitype_id' => uitype('select')->id,
            'displaytype_id' => displaytype('everywhere')->id,
            'data' => [ 'choices' => [ 0, 1, 2 ], 'default' => 2 ],
            'sequence' => 0,
            'block_id' => $block->id,
            'module_id' => $module->id,
        ]);

        // Decimals separator
        Field::create([
            'name' => 'decimals_separator',
            'uitype_id' => uitype('select')->id,
            'displaytype_id' => displaytype('everywhere')->id,
            'data' => [ 'choices' => [ '.', ',', '\'' ], 'default' => '.' ],
            'sequence' => 1,
            'block_id' => $block->id,
            'module_id' => $module->id,
        ]);

        // Thousands separator
        Field::create([
            'name' => 'thousands_separator',
            'uitype_id' => uitype('select')->id,
            'displaytype_id' => displaytype('everywhere')->id,
            'data' => [ 'choices' => [ '.', ',', 'thousands_separator.space' ], 'default' => 'thousands_separator.space' ],
            'sequence' => 2,
            'block_id' => $block->id,
            'module_id' => $module->id,
        ]);

        // Date format
        Field::create([
            'name' => 'date_format',
            'uitype_id' => uitype('select')->id,
            'displaytype_id' => displaytype('everywhere')->id,
            'data' => [ 'choices' => [ 'dd-mm-yyyy', 'mm-dd-yyyy', 'yyyy-mm-dd' ], 'default' => 'dd-mm-yyyy' ],
            'sequence' => 3,
            'block_id' => $block->id,
            'module_id' => $module->id,
        ]);
    }

    protected function createLinks($module)
    {

    }
}