<h1>THINK OUTSIDE THE BOX</h1>

[![Meavenn](http://www.meavenn.com/public/img/imgHome.jpeg)](www.meavenn.com)
> Blog professionnel qui vise à présenter ses compétences en php dans le cadre d'une formation OpenClassroom.

## Auteur
* Github: [Meavenn](https://github.com/Meavenn)

## Configuration
Ce site a été réalisé sous PHP 7.4, Twig 3 et HTML5.

## Installation
* **Etape 1 :** Transférer les fichiers dans le dossier racine de votre serveur.
* **Etape 2 :** Créer une base données MySQL. Il est possible d'importer les fichiers de démonstration proposé
* **Etape 3 :** Dans le fichier src/Config/DBConnect.php, modifier les paramètres suivants :
        $dsn = 'mysql:host=nom_du_serveur_de_la_BDD;dbname=nom_de_la_table;charset=utf8',
        $username = 'identifiant_de_connexion',
        $passwd = 'mot_de_passe_de_la_BDD'
        
Dans la class getEmailLogin() de ce même fichier, indiquer :
      'username' => 'votreadresse@gmail.com',
      'password' => 'motDePasseDeMessagerieCarotte'
Cela permettra aux visiteurs d'envoyer un email à une adresse que vous pourrez déterminer ensuite dans le backoffice du site.

## Administration du site
N'importe qui peut créer un compte. Pour cela, il faut simplement remplir le formulaire de contact.
<br>
Pour créer un premier administrateur, il faut modifier les données directement dans la base de données que vous avez créée après avoir créé le compte depuis le formulaire du site : dans le champ "status", précisez 'superadmin'.
Ensuite, vous pourrez administrer la base directement depuis le backoffice. 
<br>N'importe quel membre peut devenir administrateur si vous lui accordez les droits adéquats.
<br><br>
Il existe trois profils différents :
* membre (member) : ils sont autorisés à laisser des commentaires qui doivent être validés avant d'être affichés.
* administrateur (admin) : ils peuvent modifier le contenu de la page d'accueil du site, écrire et modifier des articles, gérer les utilisateurs et les commentaires
* superadministrateur (superadmin) : ils ont exactement le même profil que les administrateurs mais leur statut de superadministrateur ne peut pas être modifié depuis le backoffice afin de protéger leur compte.





