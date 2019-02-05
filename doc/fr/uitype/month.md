# Uitype
## Month

Cet uitype permet de renseigner une valeur de type mois de l'année.

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
| Attribut   | Description                      | Obligatoire      | Défaut        |
| ---------- | -------------------------------- | ---------------- | ------------- |
| `repeated` | Ajouter un champ de confirmation | Non              | `false`       |