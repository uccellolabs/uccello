<?php

namespace Uccello\Core\Http\Controllers\User;

use Uccello\Core\Http\Controllers\Core\Controller;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Field;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();

        $this->module = ucmodule('user');
    }

    /**
     * Display user account page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(?Domain $domain, Module $module)
    {
        $this->preProcess($domain, $module, request());

        $this->viewName = 'account.main';

        $user = auth()->user();

        return $this->autoView(compact('user'));
    }

    /**
     * Update user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(?Domain $domain, Module $module)
    {
        $this->preProcess($domain, $module, request());

        $user = auth()->user();

        $rules = Field::where('module_id', ucmodule('user')->id)->pluck('data', 'name')->map(function($item, $key) use($user) {
            return isset($item->rules) ? explode('|', str_replace('%id%', $user->id, $item->rules)) : '';
        });

        $validator = Validator::make(request()->all(), [
            'username' => $rules['username'],
            'name' => $rules['name'],
            'email' => $rules['email'],
        ]);

        if ($validator->fails()) {
            ucnotify(uctrans('notification.form.not_valid', $module), 'error');

            return redirect(ucroute('uccello.user.account', $domain))
                        ->withErrors($validator)
                        ->withInput()
                        ->with('form_name', 'profile');
        }

        $user->username = request('username');
        $user->name = request('name');
        $user->email = request('email');
        $user->save();

        ucnotify(uctrans('success.profile_updated', ucmodule('user')), 'success');

        return redirect(ucroute('uccello.user.account', $domain));
    }

    /**
     * Update user password
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(?Domain $domain, Module $module)
    {
        $this->preProcess($domain, $module, request());

        $user = auth()->user();

        $field = Field::where('module_id', ucmodule('user')->id)->where('name', 'password')->first();
        $password_rules = isset($field->data->rules) ? explode('|', $field->data->rules) : '';
        $password_rules[] = 'confirmed';

        $validator = Validator::make(request()->all(), [
            'current_password' => function($attribute, $value, $fail) use ($user) {
                if (!\Hash::check($value, $user->password)) {
                    $fail(uctrans('error.current_password', ucmodule('user')));
                }
            },
            'password' => $password_rules,
        ]);

        if ($validator->fails()) {
            ucnotify(uctrans('notification.form.not_valid', $module), 'error');

            return redirect(ucroute('uccello.user.account', $domain))
                        ->withErrors($validator)
                        ->withInput()
                        ->with('form_name', 'password');
        }

        $user->password = \Hash::make(request('password'));
        $user->save();

        ucnotify(uctrans('success.password_updated', ucmodule('user')), 'success');

        return redirect(ucroute('uccello.user.account', $domain));
    }
}