<?php

namespace Uccello\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Role;
use Uccello\Core\Models\Privilege;
use Uccello\Core\Models\Entity;
use App\User;

class UserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uccello:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

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
        $domain = Domain::first();

        $username = $this->ask(trans('uccello::command.user.username'));
        $name = $this->ask(trans('uccello::command.user.name'));
        $email = $this->ask(trans('uccello::command.user.email'));
        $password = $this->secret(trans('uccello::command.user.password'));
        $isAdmin = $this->confirm(trans('uccello::command.user.is_admin'));

        $user = User::create([
            'username' => $username,
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => $isAdmin,
            'domain_id' => $domain->id
        ]);

        // Create uuid
        Entity::create([
            'id' => (string) Str::uuid(),
            'module_id' => ucmodule('user')->id,
            'record_id' => $user->getKey(),
        ]);

        $this->addRole($domain, $user);

        $this->info(trans('uccello::command.user.user_created'));
    }

    /**
     * Add role to user
     */
    protected function addRole($domain, $user)
    {
        $roles = Role::orderBy('name')->get();
        $role = null;
        if ($roles) {
            $choices = [ ];
            foreach ($roles as $role) {
                $choices[ ] = $role->name;
            }

            $roleName = $this->choice(trans('uccello::command.user.role'), $choices);
            $role = Role::where('name', $roleName)->first();

            Privilege::firstOrCreate([
                'domain_id' => $domain->id,
                'role_id' => $role->id,
                'user_id' => $user->id
            ]);
        }
    }
}