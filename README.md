# API Documention

# USERS
```
GET /v1/users
```
Affiche la liste des utilisateurs enregistrés en base.
Auncun paramètre.

```
POST /v1/users
```
Création d'un utilisateur
Passer en paramètres : 
    - 'access_token' correspondant à un compte administrateur;
    - 'mail' du nouvel utilisateur;
    - 'login' du nouvel utilisateur.
Les autres champs sont générés automatiquement.

```
GET /v1/login
```
Connecte un utilisateur.
Nécessite 'mail' et 'password' en paramètres.

```
GET /v1/users/@id
```
Affiche les informations de l'utilisateur identifié par @id.

```
PUT /v1/users/@id
```
Mets à jour les informations de l'utilisateur identifié par @id.
Prends les paramètres :
    - 'access_token' correspondant à un compte administrateur pour obtenir les droits de modification;
    - 'password' pour l'utilisateur à modifier;
    - 'mail' pour l'utilisateur à modifier;
    - 'login' pour l'utilisateur à modifier.

```
DELETE /v1/users/@id
```
Supprime l'utilisateur identifié par @id.
Nécessite de passer en paramètre un 'access_token' correspondant à un compte administrateur.


# MOVIES
```
GET /v1/movies
```
Affiche la liste des films enregistrés en base.
Auncun paramètre.

```
POST /v1/movies
```
Création d'un film
Passer en paramètres : 
    - 'access_token' correspondant à un compte administrateur;
    - 'name' du nouveau film;
    - 'kind' du nouveau film;
    - 'description' du nouveau film.

```
GET /v1/movies/@id
```
Affiche les informations du film identifié par @id.

```
PUT /v1/movies/@id
```
Mets à jour les informations du film identifié par @id.
Prends les paramètres :
    - 'access_token' correspondant à un compte administrateur pour obtenir les droits de modification;
    - 'name' pour le film à modifier
    - 'kind' pour le film à modifier;
    - 'description' pour le film à modifier.

```
DELETE /v1/users/@id
```
Supprime le film identifié par @id.
Nécessite de passer en paramètre un 'access_token' correspondant à un compte administrateur.