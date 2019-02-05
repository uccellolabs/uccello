# Uitype
## Select

Cet uitype permet de renseigner une valeur en la sélectionnant dans une liste déroulante.

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
| Attribut   | Description                                             | Obligatoire      | Défaut        |
| ---------- | ------------------------------------------------------- | ---------------- | ------------- |
| `choices`  | Valeurs disponibles. Exemple : ['Valeur 1', 'Valeur 2'] | Oui              |               |