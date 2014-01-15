# API Documention

# Endpoints
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