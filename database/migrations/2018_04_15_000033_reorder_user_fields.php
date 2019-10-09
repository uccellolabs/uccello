<?php

use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Field;

class ReorderUserFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userModule = Module::where('name', 'user')->first();

        $emailField = Field::where('module_id', $userModule->id)
            ->where('name', 'email')
            ->first();
        $emailField->sequence = 3;
        $emailField->save();

        $passwordField = Field::where('module_id', $userModule->id)
            ->where('name', 'password')
            ->first();
        $passwordField->sequence = 4;
        $passwordField->save();

        $nameField = Field::where('module_id', $userModule->id)
            ->where('name', 'name')
            ->first();
        $nameField->data = [ 'rules' => 'required', 'icon' => 'person' ];
        $nameField->save();

        $usernameField = Field::where('module_id', $userModule->id)
            ->where('name', 'username')
            ->first();
        $usernameField->data = [ 'rules' => 'required|regex:/^[a-zA-Z0-9.-_]+$/|unique:users,username,%id%', 'icon' => 'vpn_key' ];
        $usernameField->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $userModule = Module::where('name', 'user')->first();

        $emailField = Field::where('module_id', $userModule->id)
            ->where('name', 'email')
            ->first();
        $emailField->sequence = 4;
        $emailField->save();


        $passwordField = Field::where('module_id', $userModule->id)
            ->where('name', 'password')
            ->first();
        $passwordField->sequence = 3;
        $passwordField->save();

        $nameField = Field::where('module_id', $userModule->id)
            ->where('name', 'name')
            ->first();
        $nameField->data = null;
        $nameField->save();

        $usernameField = Field::where('module_id', $userModule->id)
            ->where('name', 'username')
            ->first();
        $usernameField->data = [ 'rules' => 'required|regex:/^[a-zA-Z0-9.-_]+$/|unique:users,username,%id%', 'icon' => 'person' ];
        $usernameField->save();
    }
}
