# Uitype
## Range

Cet uitype permet de renseigner une valeur de type nombre en le sélectionnant dans un barre défilante.

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
| Attribut   | Description                              | Obligatoire      | Défaut        |
| ---------- | ---------------------------------------- | ---------------- | ------------- |
| `min`      | Valeur minimale                          | Non              | `0`           |
| `max`      | Valeur maximale                          | Non              | `100`         |
| `step`     | Incrément du curseur                     | Non              | `1`           |
| `limit`    | Écart maximal entre deux valeurs         | Non              |               |
| `margin`   | Écart minimale entre deux valeurs        | Non              |               |
| `start`    | Valeurs de départ (peut être multiple)   | Non              | `[0]`         |