# Tutoriel - Gestion du temps de travail

Nous allons voir dans ce tutoriel comment créer un outil de **gestion du temps de travail** comme ce que propose [Clockify](https://clockify.me).

> Pour suivre les **étapes précédentes**, merci de vous rendre au [début du tutoriel](./get-started.md).

## Étape 3 - Personnaliser l'interface

Dans le module **Session de travail**, nous allons modifier le comportement de la page **Liste** pour ouvrir une fenêtre modale lorsqu'on clique sur le bouton `+` , plutôt que de rediriger sur la vue **Édition**. Pour cela nous allons utiliser le principe de la [surcharge de vue](../../overriding/view.md).

Voici le résultat que nous voulons obtenir :

![Modale](/Users/jonathan/Downloads/override-show-modal.jpg)



### Surcharger la vue

Pour modifier le comportement de la vue **Liste**, nous devons créer le fichier `resources/views/modules/working-session/list/main.blade.php`. Uccello va détecter automatiquement que la vue a été surchargée et c'est celle-ci qui sera utilisée pour le module **Session de travail**.

```php+HTML
<!-- resources/views/modules/working-session/list/main.blade.php -->
@extends('uccello::modules.default.list.main')

@section('page-action-buttons')
    {{-- Create button --}}
    @if (Auth::user()->canCreate($domain, $module))
    <div id="page-action-buttons">
        <a class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float"
            title="{{ uctrans('button.new', $module) }}"
            data-toggle="tooltip"
            data-placement="top"
            data-config='{"actionType":"modal", "modal":"#addSessionModal"}'>
            <i class="material-icons">add</i>
        </a>
    </div>
    @endif
@endsection

@section('extra-content')
    {{-- Add filter modal --}}
    @include("uccello::modules.default.list.modal.add-filter")

    {{-- Add session modal --}}
    @include("modules.working-session.list.modal.add-session")
@endsection
```

#### Utilisation de la vue par défaut

On étend la vue Liste par défaut pour pouvoir la surcharger.

```
@extends('uccello::modules.default.list.main')
```

#### Modification du comportement du bouton

On surcharge la section `page-action-buttons` pour modifier le comportement du bouton d'ajout.

```php+HTML
<!-- resources/views/modules/working-session/list/main.blade.php -->
@section('page-action-buttons')
    {{-- Create button --}}
    @if (Auth::user()->canCreate($domain, $module))
    <div id="page-action-buttons">
        <a class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float"
            title="{{ uctrans('button.new', $module) }}"
            data-toggle="tooltip"
            data-placement="top"
            data-config='{"actionType":"modal", "modal":"#addSessionModal"}'>
            <i class="material-icons">add</i>
        </a>
    </div>
    @endif
@endsection
```

Voici à quoi ressemble cette section sur la **vue par défaut** utilisée par Uccello :

```php+HTML
<!-- vendor/uccello/uccello/resources/views/modules/default/list/main.blade.php -->
@section('page-action-buttons')
    {{-- Create button --}}
    @if (Auth::user()->canCreate($domain, $module))
    <div id="page-action-buttons">
        <a href="{{ ucroute('uccello.edit', $domain, $module) }}"
           class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float"
           title="{{ uctrans('button.new', $module) }}"
           data-toggle="tooltip"
           data-placement="top">
            <i class="material-icons">add</i>
        </a>
    </div>
    @endif
@show
```

On a supprimé l'attribut `href`, car on n'a plus besoin de lien vers la page Édition.

On a rajouté l'attribut `data-config` pour qu'Uccello détecte automatiquement qu'il faut ouvir une **fenêtre modale**.

```
data-config='{"actionType":"modal", "modal":"#addSessionModal"}'
```

- `"actionType":"modal"` - On veut ouvrir une fenêtre modale lors du clic sur le bouton
- `"modal":"#addSessionModal" ` - L'identifiant de la `div` contenant la modale est `id="addSessionModal"`

Grâce à cette instruction, Uccello pourra ouvrir automatiquement une fenêtre modale lors d'un clic sur le bouton `+`. 

#### Configuration de la fenêtre modale

On surcharge la section `extra-content` pour ajouter le code de la fenêtre modale.

```php+HTML
<!-- resources/views/modules/working-session/list/main.blade.php -->
@section('extra-content')
    {{-- Add filter modal --}}
    @include("uccello::modules.default.list.modal.add-filter")

    {{-- Add session modal --}}
    @include("modules.working-session.list.modal.add-session")
@endsection
```

 Voici à quoi ressemble cette section sur la **vue par défaut** utilisée par Uccello :

```php+HTML
<!-- vendor/uccello/uccello/resources/views/modules/default/list/main.blade.php -->
@section('extra-content')
    {{-- Add filter modal --}}
    @include("uccello::modules.default.list.modal.add-filter")
@endsection
```

Nous devons donc créer le fichier `resources/views/modules/working-session/list/modal/add-session.blade.php`.

```php+HTML
<!-- resources/views/modules/working-session/list/modal/add-session.blade.php -->
<div class="modal fade" id="addSessionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4>
                    <div class="block-label-with-icon">
                        <i class="material-icons">timer</i>
                        <span>{{ uctrans('modal.add_session.title', $module) }}</span>
                    </div>
                    <small>{{ uctrans('modal.add_session.description', $module) }}</small>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    {{-- Description --}}
                    <div class="col-sm-8">
                        <div class="form-group form-float">
                            <div class="input-field">
                                <div class="form-line">
                                    <label class="form-label">{{ uctrans('field.description', $module) }}</label>
                                    <input class="form-control" id="session_description" name="session_description" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Project --}}
                    <div class="col-sm-4">
                        <div class="form-group form-choice">
                            <label class="form-label">{{ uctrans('field.project', $module) }}</label>
                            <div class="input-field">
                                <select class="form-control" id="session_project" name="session_project">
                                    <option value="">&nbsp;</option>
                                    @foreach (App\Project::all() as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <a class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                            <i class="material-icons">play_arrow</i>
                        </a>
                        <span class="m-l-15 timer">00:00:00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{ uctrans('button.close', $module) }}</button>
            </div>
        </div>
    </div>
</div>
```

#### Ajout des traductions

Nous devons modifier le fichier `resources/lang/fr/working-session.php` et ajouter les traductions suivantes :

```php
// resources/lang/fr/working-session.php
return [
...
    // Button
    'button.close' => 'Fermer',

    // Modal
    'modal.add_session.title' => 'Session de travail',
    'modal.add_session.description' => 'Lancez le chronomètre pour créer facilement une nouvelle session de travail.',
];
```

## Étape 4 - Un peu de JavaScript

Maintenant que la fenêtre modale s'affiche correctement, nous devons configurer son **comportement** lorsqu'on lance et arrêtre le **chronomètre**.

Nous verrons comment faire cela dans [la suite du tutoriel](./javascript.md).