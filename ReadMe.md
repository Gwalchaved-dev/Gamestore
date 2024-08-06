# GAMESTORE - ECF Janvier/février 2025 - BJ HNT

*** Infos entreprise ***
```
Gamestore est une entreprise spécialisée dans la vente de jeu vidéo. Ils ont 5 magasins en
France à Nantes, Lille, Bordeaux, Paris ainsi que Toulouse.
Ils proposent des jeux vidéo sur toutes les plateformes connues à ce jour, mais ils n’ont pas de
site web. Pour le moment, seuls des prospectus sont effectués. Ce mode de fonctionnement
est dépassé et Gamestore commence à perdre des clients. Ils ont besoin d’avoir accès à un outil leur donnant de la visibilité et permettant la vente en ligne des jeux vidéo.
Vous avez été diplômé par l’école Studi. De plus, l’école ayant vanté vos compétences, vous
avez été pris en freelance pour réaliser cette application.
```

*** Description projet ***
```
Gamestore souhaite améliorer sa gestion. Dans cette optique, l’entreprise a besoin d’une
application lui permettant de :
->Développer sa visibilité
->Proposer de la vente en ligne (cependant, le paiement ainsi que le retrait se fera dans
une agence)
Pour ce faire, José, gérant de Gamestore, a décidé de vous prendre à part afin de vous présenter tous les éléments (CF CAHIER DES CHARGES).

```
*** KANBAN ***
* [TRELLO](https://trello.com/b/BsKkY6Gp/gamestore-bj-hnt-kanban): Tabler KANBAN - ECF - GAMESTORE

*** FIGMA ***

https://www.figma.com/design/p8sm3BDQKgfxbb5WqlIAil/Gamestore?node-id=1-2&m=dev&t=EEXlZ1F3uJrL7LYK-1


## Technologies
***
A list of technologies used within the project:

* [HTML](https://developer.mozilla.org/fr/docs/Web/HTML): HTML5
* [CSS- BOOTSTRAP](https://getbootstrap.com/): CSS3/CSS4
* [JavaScript](https://developer.mozilla.org/fr/docs/Web/JavaScript): JavaScript ES6
* [MySql](https://www.mysql.com/fr/): Version 8.3.0
* [PHP](https://www.php.net/): Version 8.3.7
* [MongoDB](https://www.mongodb.com/fr-fr): Version 7.0.12
* [Docker](https://www.docker.com/): Version 4.33
* [Node.js](https://nodejs.org/fr): Version 22.5.1
* [Bootstrap](https://getbootstrap.com/): Version 5.3.3
* [Npm](https://www.npmjs.com/): Version 10.8.2

TECHOLOGIES UTILISEES (CF WAPPALYZER, Extension Chrome)

### Installation
*** A little intro about the installation ***
```
$ mkdir -p /users/bjh/env/workplace/Gamestore
$ cd /users/bjh/env/workplace/Gamestore
```
*** Bootstrap ***
```
$ brew install node (Install of Node.js)
$ npm install
$ npm init -y
$ npm install bootstrap@5.3.3
```
*** initialisation of git repository and creation git branchs ***
```
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
$ git config pull.rebase true (In the case of branch divergence between the github repository and the local)
```

*** installation of MongoDB ***
```
MongoDB has its own repository for Homebrew. We need to add this repository first to access the latest versions of MongoDB :
$ brew tap mongodb/brew
Once the repository is typed, we can install MongoDB Community Edition:
$ brew install mongodb-community
Start mongoDB :
$ brew services start mongodb/brew/mongodb-community
Stop mongoDB :
$ brew services stop mongodb/brew/mongodb-community
To verify that MongoDB is installed and working correctly, we can use the following command to access the MongoDB console: mongosh (since version 5.0)
This will connect you to the running MongoDB instance. If we see the MongoDB shell (>) appear, the installation was successful.
```

Notes Importantes

	•	Configuration par défaut : MongoDB est installé avec une configuration par défaut qui utilise /usr/local/var/mongodb pour le stockage des données et /usr/local/etc/mongod.conf pour le fichier de configuration.
	•	Fichier de configuration : Tu peux modifier les configurations MongoDB en éditant le fichier /usr/local/etc/mongod.conf.
	•	Droits d’accès : Assure-toi d’avoir les permissions nécessaires pour écrire dans les répertoires utilisés par MongoDB. Si tu rencontres des erreurs de permission, tu peux changer les permissions ou exécuter les commandes avec sudo.




### DIAGRAMMES :

![Cas d'utilisation](/images/Diagramme%20cas%20utilisations%20Gamestore.drawio.png)
![Séquences]()

#### Security
*** 
```
Sous mac OS (with Homebrew) :
$ brew update
$ brew upgrade
(Updating and upgrading differents softwares: openssl, etc... to the latest stable versions)
```

