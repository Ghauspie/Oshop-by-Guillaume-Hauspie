<?php

namespace App\Controllers;

use App\Models\AppUser;


/**
 * Controller dédié à l'affichage des catégories
 */
class LoginController extends CoreController
{
    public function login()
    {
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('main/login');
    }

    public function connected(){
          // On tente de récupèrer les données venant du formulaire.
          $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
          $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

       
        // On créer le tableau d'erreur'
          $errorsList=[];

          // On verifier que $email et $password sont bien remplis
          if (empty($email)) {
            $errorsList[] = 'L\'email est vide';
        }
       if ($email === false) {
            $errorsList[] = 'L\'email est invalide';
        }
        // Pareil pour le "subtitle".
        if (empty($password)) {
            $errorsList[] = 'Le password est vide';
        }
        if ($password === false) {
            $errorsList[] = 'Le password est invalide';
        }
        //dump($errorsList);
        //die;
        // On verifie qu'il n'y a aucune erreur présente dans $errorslist
        if (empty($errorsList)){

            
            // On instance la connexion au serveur
            // On recupere le user correspondant a l'email
            $Login = new AppUser();//::findByEmail($email);
            $testconnected= $Login -> findByEmail($email);
            
           
            // On recuperer les propriétés de l'instance.
            $Login->getEmail($email);           
            $Login->getPassword($password);
            dump($testconnected);
            die;
            // On tente de sauvegarder les données en DB...
            //if ($this->connected()) {
                // Si la sauvegarde a fonctionné, on redirige vers la liste des catégories.
               // header('Location: /');
                //session_start();
               // exit;
            }
            else {
                // Sinon, on ajoute un message d'erreur à la page actuelle, et on laisse
                // l'utilisateur retenter la création.
                $errorsList[] = 'La sauvegarde a échoué';
            }

        }



    }

