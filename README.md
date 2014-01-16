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
    - 'access_token' (méthode GET) correspondant à un compte administrateur;
    - 'mail' (méthode POST) du nouvel utilisateur;
    - 'login' (méthode POST) du nouvel utilisateur.
Les autres champs sont générés automatiquement.

```
GET /v1/login
```
Connecte un utilisateur.
Passer en paramètre : 
    - 'mail' (méthode GET) correspondant au mail de l'utilisateur;
    - 'password' (méthode GET) correspondant au mot de passe de l'utilisateur.

```
GET /v1/users/@id
```
Récupère les informations de l'utilisateur identifié par @id.

```
PUT /v1/users/@id
```
Mets à jour les informations de l'utilisateur identifié par @id.
Prends les paramètres :
    - 'access_token' (méthode GET) correspondant à un compte administrateur pour obtenir les droits de modification;
    - 'password' (méthode PUT) pour l'utilisateur à modifier;
    - 'mail' (méthode PUT) pour l'utilisateur à modifier;
    - 'login' (méthode PUT) pour l'utilisateur à modifier.

```
DELETE /v1/users/@id
```
Supprime l'utilisateur identifié par @id.
Prends le paramètre : 
    - 'access_token' (méthode GET) correspondant à un compte administrateur pour obtenir les droits de suppression.


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
    - 'access_token' (méthode GET) correspondant à un compte administrateur;
    - 'name' (méthode PUT) du nouveau film;
    - 'kind' (méthode PUT) du nouveau film;
    - 'description' (méthode PUT) du nouveau film.

```
GET /v1/movies/@id
```
Affiche les informations du film identifié par @id.

```
PUT /v1/movies/@id
```
Mets à jour les informations du film identifié par @id.
Prends les paramètres :
    - 'access_token' (méthode GET) correspondant à un compte administrateur pour obtenir les droits de modification;
    - 'name' (méthode PUT) pour le film à modifier
    - 'kind' (méthode PUT) pour le film à modifier;
    - 'description' (méthode PUT) pour le film à modifier.

```
DELETE /v1/users/@id
```
Supprime le film identifié par @id.
Nécessite de passer en paramètre un 'access_token' (méthode GET) correspondant à un compte administrateur.


# LIKES, VIEWS AND WISHES

```
POST /v1/movies/@id
```
Permet, à un utilisateur, d'ajouter le film désigné par @id comme like, view ou wish.
Cela se fait en passant en paramètre au choix like, view ou wish (1 seul possible), et comme valeur l'id de l'utilisateur.
ex. : /v1/movies/2?like=3
Ici, l'utilisateur 3 va liker le film 2.

ex. : /v1/movies/4?wish=2
Ici, l'utilisateur 2 va "souhaiter voir" le film 4.

Méthode POST pour le passage du paramètre.

```
GET /v1/users/@id/views
```
Permet d'afficher les films marqués comme "view" par l'utilisateur @id.
Pas de paramètre.

```
GET /v1/users/@id/likes
```
Permet d'afficher les films marqués comme "like" par l'utilisateur @id.
Pas de paramètre.

```
GET /v1/users/@id/wishes
```
Permet d'afficher les films marqués comme "wish" par l'utilisateur @id.
Pas de paramètre.

```
DELETE /v1/users/@idUser/DeleteView/@idMovie
```
Permet à l'utilisateur @idUser de supprimer le film @idMovie de sa liste de "vus".

```
DELETE /v1/users/@idUser/DeleteLike/@idMovie
```
Permet à l'utilisateur @idUser de supprimer le film @idMovie de sa liste de "like".

```
DELETE /v1/users/@idUser/DeleteWish/@idMovie
```
Permet à l'utilisateur @idUser de supprimer le film @idMovie de sa liste de "souhaits".