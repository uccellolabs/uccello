@extends('uccello::modules.default.list.main')

@if (uccello()->useMultiDomains())
    @section('page-action-buttons')
        {{-- Create button --}}
        @if (Auth::user()->canCreate($domain, $module))
        <div id="page-action-buttons">
            <a href="#importUserModal" class="btn-floating btn-large waves-effect modal-trigger orange" data-tooltip="{{ uctrans('button.import_user', $module) }}" data-position="top">
                <i class="material-icons">person_add</i>
            </a>

            <a href="{{ ucroute('uccello.edit', $domain, $module) }}" class="btn-floating btn-large waves-effect green" data-tooltip="{{ uctrans('button.new', $module) }}" data-position="top">
                <i class="material-icons">add</i>
            </a>
        </div>
        @endif
    @endsection

    @section('extra-content')
        <div id="importUserModal" class="modal">
            <form action="{{ ucroute('uccello.user.import', $domain, $module) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <h4>
                        <i class="material-icons left orange-text">person_add</i>
                        {{ uctrans('modal.import_user.title', $module) }}
                    </h4>

                    <p>{{ uctrans('modal.import_user.description', $module) }}</p>

                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <input id="user_name" class="autocomplete emptyable" type="text" data-url="{{ ucroute('uccello.autocomplete', $domain, $module) }}">
                                <label for="user_name" class="required">{{ uctrans('modal.import_user.name', $module) }}</label>
                                <input id="user_id" name="user_id" type="hidden" data-url="{{ ucroute('uccello.autocomplete', $domain, $module) }}">
                            </div>
                        </div>

                        <div id="domain_roles" class="row hide">
                            <div class="input-field col s12">
                                {{ Form::select(
                                    'roles[]',
                                    $roles,
                                    null,
                                    [ 'multiple' => 'multiple' ]) }}
                                <label class="required">{{ uctrans('block.roles', $module) }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0)" class="btn-flat waves-effect modal-close">{{ uctrans('button.cancel', $module) }}</a>
                    <button type="submit" class="btn-flat waves-effect green-text save" disabled>{{ uctrans('button.save', $module) }}</button>
                </div>
            </form>
        </div>
    @endsection

    @section('uccello-extra-script')
        {!! Html::script(mix('js/user/autoloader.js', 'vendor/uccello/uccello')) !!}
    @append
@endif
