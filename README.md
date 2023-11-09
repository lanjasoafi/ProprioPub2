Annonces Immobilières Symfony
Bienvenue dans le projet "ProprioPub" ! Ce projet est une application web basée sur le framework Symfony qui permet de publier, gérer et afficher des annonces immobilières. Que vous soyez un utilisateur à la recherche d'une nouvelle maison ou un administrateur souhaitant gérer les annonces, cette application vous couvre.

Prérequis
Avant de commencer, assurez-vous d'avoir installé les éléments suivants sur votre machine :

PHP (version recommandée)
Composer
Symfony CLI
MySQL
Installation
Clonez ce dépôt sur votre machine :

bash
Copy code
git clone https://github.com/lanjasoafi/ProprioPub2.git
Installez les dépendances du projet à l'aide de Composer :

bash
Copy code
composer install
Configurez votre base de données dans le fichier .env en spécifiant les informations de connexion à votre base de données.

Créez la base de données et exécutez les migrations pour créer les tables nécessaires :

bash
Copy code
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
Lancez le serveur de développement Symfony :

bash
Copy code
symfony serve
Accédez à l'application dans votre navigateur à l'adresse http://127.0.0.1:8000.

Utilisation
Les utilisateurs peuvent parcourir les annonces, effectuer des recherches, et afficher les détails des annonces.
Les administrateurs peuvent gérer les annonces en ajoutant, modifiant ou supprimant des annonces depuis le tableau de bord d'administration.
