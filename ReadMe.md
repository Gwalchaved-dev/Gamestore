# GAMESTORE - ECF (MarkDown)

*** Infos entreprise ***
Gamestore est une entreprise spécialisée dans la vente de jeu vidéo. Ils ont 5 magasins en
France à Nantes, Lille, Bordeaux, Paris ainsi que Toulouse.
Ils proposent des jeux vidéo sur toutes les plateformes connues à ce jour, mais ils n’ont pas de
site web. Pour le moment, seuls des prospectus sont effectués. Ce mode de fonctionnement
est dépassé et Gamestore commence à perdre des clients. Ils ont besoin d’avoir accès à un outil leur donnant de la visibilité et permettant la vente en ligne des jeux vidéo.
Vous avez été diplômé par l’école Studi. De plus, l’école ayant vanté vos compétences, vous
avez été pris en freelance pour réaliser cette application.

*** Description projet ***
Gamestore souhaite améliorer sa gestion. Dans cette optique, l’entreprise a besoin d’une
application lui permettant de :
->Développer sa visibilité
->Proposer de la vente en ligne (cependant, le paiement ainsi que le retrait se fera dans
une agence)
Pour ce faire, José, gérant de Gamestore, a décidé de vous prendre à part afin de vous présenter tous les éléments (CF CAHIER DES CHARGES).


## Technologies
***
A list of technologies used within the project:
* [HTML](https://example.com): Version 12.3 
* [CSS- BOOTSTRAP](https://example.com): Version 2.34
* [CSS- BOOTSTRAP](https://example.com): Version 2.34
* [Library name](https://example.com): Version 1234

TECHOLOGIES UTILISEES (CF WAPPALYZER, Extension Chrome)

### Installation
*** A little intro about the installation ***
```
$ mkdir -p /users/bjh/env/workplace/Gamestore
$ cd /users/bjh/env/workplace/Gamestore
$ npm install
$ npm star
```
*** initialisation of git repository and creation git branchs ***
$ git init
$ git commit -m "premier commit" (if files already exists)
$ git remote add origin https://github.com/Gwalchaved-dev/Gamestore.git (add distant github repository to my local repository)
$ git branch -M main 
$ git push -u origine main (creation and push main branch)
$ git checkout -b develop (creation and switch in developpement branch)
$ git push -u origin develop (Push new branch to github)
$ git checkout -b feature/nom-de-la-fonctionnalité (create new branch since develop for all functionality)
$ git add .
$ git commit -m "Description du commit pour la fonctionnalité"
$ git push -u origin feature/nom-de-la-fonctionnalité (Before merge, make functionality modification and push in the specific branch)
$ git checkout develop (Once development and testing are complete, switch back to the develop branch)
$ git merge feature/nom-de-la-fonctionnalité (merge the specific branch of functionality to the develop branch)
$ git push origin develop (and push in develop branch)
$ git branch -d feature/nom-de-la-fonctionnalité
$ git push origin --delete feature/nom-de-la-fonctionnalité (optional, removing specific feature branches, if no longer necessary)

```
Side information: To use the application in a special environment use ```lorem ipsum``` to start

