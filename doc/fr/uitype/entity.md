# Uitype
## Entity

Cet uitype permet de renseigner une valeur en la sélectionnant dans une liste de données provevant d'un autre module.

Les propriétés de l'uitype sont spécifiées dans la colonne `data` de la table `uccello_fields`.

### Propriétés communes à tous les uitypes

| Attribut   | Description                                                                                                   | Défaut         |
| ---------- | ------------------------------------------------------------------------------------------------------------- | -------------- |
| `column`   | Nom de la colonne associée dans la table du module                                                            | `$field->name` |
| `default`  | Valeur par défaut                                                                                             |                |
| `icon`     | Icône associée                                                                                                |                |
| `label`    | Libellé à utiliser pour la traduction                                                                         | `$field->name` |
| `large`    | Afficher le champ sur deux colonnes                                                                           | `false`        |
| `required` | Champ obligatoire                                                                                             | `false`        |
| `rules`    | Règles de validation du formulaire (voir [Documentation officielle](https://laravel.com/docs/5.7/validation)) |                |

### Propriétés spécifiques
| Attribut              | Description                                        | Obligatoire    | Défaut        |
| --------------------- | -------------------------------------------------- | -------------- | ------------- |
| `autocomplete_search` | Active la complétion automatique pour la recherche | Non            | `false`       |
| `module`              | Nom du module lié                                  | Oui            |               |
| `field`               | Nom de la propriété à afficher                     | Non            |               |