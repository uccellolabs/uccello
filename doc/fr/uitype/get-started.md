# Uitype
## Présentation
`Uitype` : Élément graphique permettant de représenter un champ de formulaire et de formater sa valeur en fonction d'un contexte.

**Uccello** permet de créer des formulaires constitués de champs de différents types. Chaque **type de champ** correspond à un `uitype`.

Chaque `uitype` est **autonome**, **indépendant** et **capable de formater** des données :
- Dans une `base de données`
- Dans une `vue`
- Dans une cellule d'un tableau `Datatables`
- Dans la réponse d'un appel `API`

De même, chaque `uitype` est capable de générer un champ de formulaire à afficher sur une page `édition`.

### Flexible
Grâce à la grande flexibilité d'**uccello**, il est possible de créer des `uitypes` **simplistes** (une simple valeur) tout comme des `uitypes` **complexes** (affichage utilisant des librairies externes).

Par exemple l'uitype `text` se contente d'afficher du texte brut alors que l'uitype `entity` va pouvoir lier une donnée à une autre pouvant se situer dans un module différent. L'uitype `date` permettra quant à lui de sélectionner une date dans un calendrier.

### Configurable
La configuration d'un `uitype` se fait à plusieurs niveaux.

- Fichier de `migration` : Type de la colonne dans la base de données et type du champ utilisé dans le formulaire
- Classe `php` : Formatage de la valeur lors de la sauvegarde et de l'affichage
- Classe `javascript` : Représentation de la cellule dans un tableau `Datatables`
- Fichiers `blade` : Affichage du champ du formulaire et mise en page dans une vue

Prenons l'exemple de l'uitype `text` :

#### Fichier de migration
``` php
// Création de la table
Schema::create('people', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name'); // Cette colonne utilisera l'uitype text
    $table->timestamps();
});

...

// Création du champ
$field = new Field();
$field->name = 'name';
$field->uitype_id = uitype('text')->id; // Utilisation de l'uitype text
$field->displaytype_id = displaytype('everywhere')->id;
$field->sequence = 0;
$field->block_id = $block->id;
$field->module_id = $module->id;
$field->save();
```

`$table->string('name')` : Créer une colonne `name` de type `VARCHAR(255)`

`$field->uitype_id = uitype('text')->id` : Le champ utilise l'uitype `text`


#### Classe php

L'uitype `text` est défini dans le fichier `app/Fields/Uitype/Text.php`. En voici une synthèse après intégration des traits utilisés.

``` php
namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Domain;

class Text implements Uitype
{
    /**
     * Nom du package dans lequel se trouve l'uitype
     * Utile à Blade pour retrouver les fichiers de template associés
     */
    public $package = 'uccello';

    /**
     * Type correspondant dans Form Builder
     */
    public function getFormType(): string
    {
        return 'text';
    }

    /**
     * Options du champ dans Form Builder
     */
    public function getFormOptions($record, Field $field, Domain $domain, Module $module): array
    {
        return [];
    }

    /**
     * Nom par défaut de la colonne dans la base de données
     */
    public function getDefaultDatabaseColumn(Field $field) : string
    {
        return $field->name;
    }

    /**
     * Icône a utiliser par défaut dans le champ du formulaire
     */
    public function getDefaultIcon() : ?string
    {
        return null;
    }

    /**
     * Formatage de la valeur pour un affichage dans une vue ou dans une réponse API
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        return $record->{$field->column} ?? '';
    }

    /**
     * Formatage de la valeur avant sa sauvegarde dans la base de données
     */
    public function getFormattedValueToSave(Request $request, Field $field, $value, $record=null, ?Domain $domain=null, ?Module $module=null) : ?string
    {
        return $value;
    }

    /**
     * Formatage de la valeur avant la recherche dans la base de données
     */
    public function getFormattedValueToSearch($value) : string
    {
        $formattedValue = $value;

        if ($formattedValue) {
            $formattedValue = "%$value%";
        }

        return $formattedValue;
    }

    /**
     * Ajout de la condition dans la requête SQL
     */
    public function addConditionToSearchQuery(Builder $query, Field $field, $value)
    {
        $formattedValue = $this->getFormattedValueToSearch($value);
        $query = $query->where($field->column, 'like', $formattedValue);

        return $query;
    }
}
```

#### Classe JavasSript
La représentation de l'uitype `text` dans les cellules de tableaux `Datatables` est assurée par le fichier `resources/assets/js/uitypes/text.js`.

``` javascript
export class UitypeText
{
    // Aucun traitement n'est nécessaire. Le texte est affiché tel quel dans la cellule
    createdCell(columnData, td, cellData, rowData, row, col) {
        // Ne rien faire
    }
}
```

#### Fichiers blade
Il existe au moins **deux fichiers** blade par `uitype`. Un pour la mise en page du champ du formulaire de la vue `édition` et un pour l'affichage de la valeur du champ dans la vue `détail`.

Par défaut, l'uitype `text` utilise les fichiers blade `resources/views/modules/default/uitypes/edit/text.blade.php`, `resources/views/modules/default/uitypes/detail/text.blade.php` et
`resources/views/modules/default/uitypes/search/text.blade.php`.

Voici le contenu du fichier `resources/views/modules/default/uitypes/detail/text.blade.php`.

``` html
<?php $isLarge = $field->data->large ?? false; ?>
<div class="col-sm-2 col-xs-5">
    <strong>{{ uctrans($field->label, $module) }}</strong>
</div>
<div class="{{ $isLarge ? 'col-sm-10 col-xs-7' : 'col-sm-4 col-xs-7' }}">
    {{ $field->uitype->getFormattedValueToDisplay($field, $record) }}&nbsp;
</div>
```

On remarque l'utilisation de la méthode `getFormattedValueToDisplay(Field $field, $record)` définie dans la classe `php`.

### Extensible
Grâce au fait que chaque `uitype` est **autonome** et **indépendant**, il est possible d'en créer de nouveaux et de les partager avec la communauté **uccello**.

Pour créer un nouvel `uitype` vous pouvez suivre le tutoriel [Créer un nouvel uitype](./create-new-uitype.md).

### Liste des uitypes natifs
Les `uitypes` natifs se trouvent dans le répertoire `app/Fields/Uitype`.

Pour avoir un aperçu de tous les `uitypes` natifs, vous pouvez vous rendre à la page [Liste des uitypes natifs](./native-uitypes.md).