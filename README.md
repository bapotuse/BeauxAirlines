
# BeauxAirlines

Gestion de pilotes et d'aéroports


## Authors

- [@bapotuse](https://www.github.com/bapotuse)


## Routes à savoir (ATH)

Vous pouvez accéder à l'ATH en direct des aéroports en faisant cette route (si vous êtes en local, ce sera localhost:8000/... , sinon ce sera beauxairlines.bapotuse.fr/...)

Pour avoir les vols qui partent de l'aéroport choisi :

``
/AthAller/{idAeroport}/
``

Pour avoir les vols qui arrivent de l'aéroport choisi : 

``
/AthRetour/{idAeroport}/
``

## Dépendances 

Pour installer les dépendances

```bash
  npm install
```

## Déploiement 

Pour démarrer le projet en local 

( Assurez vous d'avoir php sur votre ordinateur )

```bash
  php -S localhost:8000 -t public
```


