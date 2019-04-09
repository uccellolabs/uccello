<?php

namespace Uccello\Core\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Hash;
use Uccello\Core\Models\Domain;

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
        $username = $this->ask(trans('uccello::command.user.username'));
        $firstName = $this->ask(trans('uccello::command.user.first_name'));
        $lastName = $this->ask(trans('uccello::command.user.last_name'));
        $email = $this->ask(trans('uccello::command.user.email'));
        $password = $this->secret(trans('uccello::command.user.password'));
        $isAdmin = $this->ask(trans('uccello::command.user.is_admin'), true);

        User::create([
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => $isAdmin,
            'domain_id' => Domain::first()->id
        ]);

        $this->info(trans('uccello::command.user.user_created'));
    }
}