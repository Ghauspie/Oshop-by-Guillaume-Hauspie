<?php

namespace App\Controllers;

use App\Models\AppUser;


/**
 * Controller dédié à l'affichage des catégories
 */
class UserController extends CoreController
{
    public function login()
    {
        var_dump($_SESSION);
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('user/login');
    }

    public function connected(){
          // On tente de récupèrer les données venant du formulaire.
          $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
          $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        // On créer le tableau d'erreur'
        $errorsList=[];
            // On instance la connexion au serveur
            // On recupere le user correspondant a l'email
        $Login = AppUser::findByEmail($email);
        
        
          // On verifier que $email et $password sont bien remplis
          if (empty($email)) {
            $errorsList[] = 'L\'email est vide';
        }
        // Pareil pour le "subtitle".
        if (empty($password)) {
            $errorsList[] = 'Le password est vide';
        }
           
        if (!$Login && !empty($email)) {
            $errorsList[] = 'Utilisateur inconnu.';
        }
           // Vérification du mot de passe saisi dans le form
        if ($Login && !empty($password) && !password_verify($password,$Login->getPassword())) {
            $errorsList[] = 'Mot de passe invalide !';
        }
        // si on a des erreurs
        if (!empty($errorsList)){
            var_dump($errorsList);
        }
        // sinon on connecte l'utilisateur
        else {
            
            $_SESSION['userId'] = $Login->getId();
            $_SESSION['userObject'] = $Login;
            header('Location: /');
            var_dump($_SESSION);
        }
         
        }
        
    public function disconnect(){
        //delicatesse
        //unset( $_SESSION['userId'] );
        //unset( $_SESSION['userObject'] );
        session_destroy();
        header('Location: /user/login');
        
    }  

    public function list()
    {
        //On verifie que l'utilisateur a les droits pour aller sur cette page
        $this->checkAuthorization(['admin']);
        // On récupère toutes les catégories
        $userModel = new AppUser();
        $users = $userModel->findAll();
        // var_dump($products);

        // On les envoie à la vue
        $this->show(
            '/user/user',
            ['users' => $users]
        );
    }
    public function add()
    {
        $this->checkAuthorization(['admin']);
        $this->show(
            'user/add-edit',
            [
                'user' => new AppUser()
            ]
        );
    }
    public function update($userId)
    {
        $this->checkAuthorization(['admin']);
        // On récupère notre catégorie
        $userModel = new AppUser();
        $user = $userModel->find($userId);

        // On affiche notre vue en transmettant les infos du produit
        $this->show(
            'user/add-edit',
            [
                'user' => $user
            ]
        );
    }

    /**
     * POST Création d'une catégorie.
     *
     * @return void
     */
    public function create()
    {
        $this->checkAuthorization(['admin']);
        // On tente de récupèrer les données venant du formulaire.
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $lastname	 = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);

        // On vérifie l'existence et la validité de ces données (gestion d'erreur).
        $errorsList = [];

        // Pour l' "email", faut vérifier si la chaîne est présente *et* si elle
        // a passé le filtre de validation.
        if (empty($email)) {
            $errorsList[] = 'L\'email est vide';
        }
        if ($email === false) {
            $errorsList[] = 'L\'email est invalide';
        }
        // Pareil pour le "password".
        if (empty($password)) {
            $errorsList[] = 'Le mot de pass est vide';
        }
        if ($password === false) {
            $errorsList[] = 'Le mot de pass est invalide';
        }
        if (empty($firstname)) {
            $errorsList[] = 'Le prénom est vide';
        }
        if (empty($lastname)) {
            $errorsList[] = 'Le nom de famille est vide';
        }
        if (empty($role)) {
            $errorsList[] = 'Le rôle est vide';
        }
        if (empty($status)) {
            $errorsList[] = 'Le status est vide';
        }

        // S'il n'y a aucune erreur dans les données...
        if (empty($errorsList)) {
            // On instancie un nouveau modèle de type Category.
            $user = new AppUser();

            // On met à jour les propriétés de l'instance.
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setRole($role);
            $user->setStatus($status);

            // On tente de sauvegarder les données en DB...
            if ($user->insert()) {
                // Si la sauvegarde a fonctionné, on redirige vers la liste des catégories.
                header('Location: /user/list');
                exit;
            }
            else {
                // Sinon, on ajoute un message d'erreur à la page actuelle, et on laisse
                // l'utilisateur retenter la création.
                $errorsList[] = 'La sauvegarde a échoué';
            }
        }

        // S'il y a une ou de(s) erreur(s) dans les données...
        else {
            // On réaffiche le formulaire, mais pré-rempli avec les (mauvaises) données
            // proposées par l'utilisateur.
            // Pour ce faire, on instancie un modèle Category, qu'on passe au template.

            $user = new AppUser();
            $user->setEmail(filter_input(INPUT_POST, 'email'));
            $user->setPassword(filter_input(INPUT_POST, 'password'));
            $user->setFirstname(filter_input(INPUT_POST, 'firstname'));
            $user->setLastname(filter_input(INPUT_POST, 'lastname'));
            $user->setRole(filter_input(INPUT_POST, 'role'));
            $user->setStatus(filter_input(INPUT_POST, 'status'));

            $this->show(
                'user/add-edit',
                [
                    // On pré-remplit les inputs avec les données BRUTES initialement
                    // reçues en POST, qui sont actuellement stockées dans le modèle.
                    'user' => $user,
                    // On transmet aussi le tableau d'erreurs, pour avertir l'utilisateur.
                    'errorsList' => $errorsList
                ]
            );
        }
    }


    }
