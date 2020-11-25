<?php

namespace App\Controllers;

use App\Models\AppUser;

/**
 * Contrôleur permettant de gérer les pages et actions liées aux utilisateurs
 */
class UserController extends CoreController {

    /**
     * Afficher le formulaire de connexion
     */
    public function login()
    {
        // var_dump($_SESSION);
        $this->show('user/login');
    }

    /**
     * Gestion de la connexion de l'utilisateur depuis le formulaire de login
     */
    public function loginPost()
    {
        // On commence par récupérer les données soumises depuis le form
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        // var_dump($email, $password);

        $errorsList = [];

        // Vérifier l'existence du user
        $currentUser = AppUser::findByEmail($email);

        if (empty($password)) {
            $errorsList[] = 'Veuillez saisir un mot de passe.';
        }

        if (empty($email)) {
            $errorsList[] = 'Veuillez saisir une adresse email.';
        }
        
        else if (!$currentUser) {
            $errorsList[] = 'Utilisateur inconnu.';
        }

        // Vérification du mot de passe saisi dans le form
        if (
                $currentUser // si l'utilisateur existe => true / sinon => false
                && !empty($password) // si le mot de passe n'est pas vide => true / sinon false
                && !password_verify($password, $currentUser->getPassword()) // si la vérification du mot de passe échoue => true / sinon => false
            )
        {
            $errorsList[] = 'Mot de passe invalide !';
        }

        // si on a des erreurs, 
        if (!empty($errorsList)) {
            var_dump($errorsList);
        }
        // sinon, on connecte l'utilisateur
        else {
            $_SESSION['userId'] = $currentUser->getId();
            $_SESSION['userObject'] = $currentUser;
            // var_dump($_SESSION);
            header('Location: /');
        }

        // var_dump($currentUser);
    }

    public function disconnect()
    {
        session_destroy();
        header('Location: /user/login');
    }

}