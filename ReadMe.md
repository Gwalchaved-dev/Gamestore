# GAMESTORE - ECF Janvier/février 2025 - BJ HNT

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


*** Kanban ***
* [TRELLO](https://trello.com/b/BsKkY6Gp/gamestore-bj-hnt-kanban): Tabler KANBAN - ECF - GAMESTORE

*** Figma ***

* [FIGMA](https://www.figma.com/design/p8sm3BDQKgfxbb5WqlIAil/Gamestore?node-id=1-2&m=dev&t=EEXlZ1F3uJrL7LYK-1): Maquette projet Gamestore.


Réalisation des wireframes et mockups sur figma. (cf lien ci-dessus)
Les test d'accessibilité ont été réalisés à l'aide du plug-in Stark Contrasts, permettant de tester la lisibilité de la maquette en testant les polices et tailles de caractères utilisés (ici obtention des notes AA et AAA), et permettent aussi de simuler un rendu en fonction des différents "handicaps" visuels afin d'avoir un aperçu de l'accessibilité du site. (ici le site aura un rendu correct pour une majortité de visiteurs).


## Technologies
*** Choix des technologies ***


*** A list of technologies used within the project: ***

*** Front-End ***

* [HTML](https://developer.mozilla.org/fr/docs/Web/HTML): HTML5
* [CSS- BOOTSTRAP](https://getbootstrap.com/): CSS3/CSS4
* [JavaScript](https://developer.mozilla.org/fr/docs/Web/JavaScript): JavaScript ES6
* [Bootstrap](https://getbootstrap.com/): Version 5.3.3
* [Npm](https://www.npmjs.com/): Version 10.8.2
* [Sass](https://sass-lang.com/): Version 1.77.8

*** Back-End ***

* [MySql](https://www.mysql.com/fr/): Version 8.3.0
* [PHP](https://www.php.net/): Version 8.3.7
* [MongoDB](https://www.mongodb.com/fr-fr): Version 7.0.12
* [Docker](https://www.docker.com/): Version 4.33
* [Node.js](https://nodejs.org/fr): Version 22.5.1

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

• Configuration par défaut : MongoDB est installé avec une configuration par défaut qui utilise /usr/local/var/mongodb pour le stockage des données et /usr/local/etc/mongod.conf pour le fichier de configuration.
• Fichier de configuration : Tu peux modifier les configurations MongoDB en éditant le fichier /usr/local/etc/mongod.conf.
• Droits d’accès : Assure-toi d’avoir les permissions nécessaires pour écrire dans les répertoires utilisés par MongoDB. Si tu rencontres des erreurs de permission, tu peux changer les permissions ou exécuter les commandes avec sudo.

*** installation of SASS ***
```
$ npm install -g sass
other possibility with Homebrew, for install Dart Sass :
$ brew install sass/sass/sass
```
*** installation of Symfony and create BDD game_store_bdd ***

```
$ composer create-project symfony/skeleton game_store_bdd
```


### Diagrammes :
*** Cas d'utilisations ***
![Cas d'utilisation](Assets/images/Diagrammes/Diagramme%20cas%20utilisations%20Gamestore.drawio.png)
*** Séquence de connexion ***
![Séquence de connexion](Assets/images/Diagrammes/Diag%20Seq%20Connexion.png)
*** Séquence de vente en ligne ***
![Séquence de vente en ligne](Assets/images/Diagrammes/Diag%20seq%20Vente%20en%20ligne.png)
*** Séquence de création/modification d'employés ***
![Séquence de création/modification employés](Assets/images/Diagrammes/Diag%20Seq%20Crea:delete:modif%20employee.png)

#### Security
*** 
Sous mac OS (with Homebrew) :
```
$ brew update
$ brew upgrade
(Updating and upgrading differents softwares: openssl, etc... to the latest stable versions)
```

##### Référencement
*** Recherche de mots clés ***

• Use Google TRENDS : https://trends.google.fr/trends/

• Voici notre liste de mots clés sélectionnés avec goolgle TREND pour un magasin de jeux-vidéos, au vu de la taille du projet nous avons opté pour l'usage de 3 mots clés / page : 
```
Jeux vidéos - RPG - FPS - Builder - Magasins de jeux
```
*** SEO ***
```
• Netlinking (maillage interne)

• Maillage Externe : Backlinks et liens sortants, dans le cadre de notre activité de ventes de jeux vidéos, il sera intéressant d'avoir des liens sortants pertinents tel que le site du studio de production du jeux en questions, ou éventuellements des sites officiels de la communauté pour certains jeux MMO... Pour le nombres de liens à intégrer sur son site, Google (en 2011) ne recommander pas d'avoirs plus d'une centaines de liens, même si cette règle ne tiens plus vraiment aujourd'hui, il faut faire preuve de discernement et surtout s'assurer ques les liens qu'ils soient interne ou externe, soient pertinents et importants pour le contenu mais aussi pour l'utilisateur.
```
*** SEA ***
```
• Fonction des besoins du clients, peut-être sera t'il pertinent d'attribuer un budget en SEA afin d'apparaitre dans les Annonces de début de SERP.

• Use Google Ads : https://ads.google.com
```
*** METADONNEES ***
```
```

*** Attributs ALT ***
```
Nous avons employés des attrbuts ALT  pour chaque image (Img) lors du développement de notre code.

```

###### Déploiement
*** Publication ***
```
https://www.netlify.com/
Use FilleZilla for the FTP transfert.
Capture d'écran
```
*** Tests performances (et améliorations) ***
```
• Use Google PageSpeeds Insights: https://developers.google.com/speed/docs/insights/v5/about?hl=fr

(inclure capture)

```
*** Test compatibilité & responsive ***
```
• Nous pourrons tester notre site sous différents OS : Microsoft, Mac, Linux et les OS mobile Android et IOS.
• Collecter les feedbacks (retour clients).
• L'aspect responsive à été pris en charge tout du long du développement, avec la méthode de mobile first, ainsi qu'avec l'usage des frameworks tels que BOOTSTRAP. Certains site permettent de réaliser ces tests, tel que : http://www.responsinator.com/ (il est possible de les réaliser directement via F12 sur Chrome)
• Enfin il nous faut tester notre site sur différents navigateurs internet: Chrome, Firefox, Safari.

```
