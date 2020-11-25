<?php

namespace App\Controllers;

use App\Controllers\ErrorController;

abstract class CoreController {
    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewVars Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewVars = []) {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        // De cette manière, on a accès à $router dans les vues (car on a modifié sa portée)
        global $router;

        // Comme $viewVars est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewVars['currentPage'] = $viewName; 

        // définir l'url absolue pour nos assets
        $viewVars['assetsBaseUri'] = $_SERVER['BASE_URI'] . '/assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewVars['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewVars, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewVars);
        // var_dump($viewVars);
        // var_dump($baseUri);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewVars est disponible dans chaque fichier de vue
        require_once __DIR__.'/../views/layout/header.tpl.php';
        require_once __DIR__.'/../views/'.$viewName.'.tpl.php';
        require_once __DIR__.'/../views/layout/footer.tpl.php';
    }

    /**
     * Méthode de vérification des droits du user courant pour faire telle ou telle action
     *
     * @param array $roles
     * @return bool return true si ok, redirige vers une erreur si pas ok
     */
    public static function checkAuthorization($roles=[]) {

        global $router;

        // Si l'utilisateur est connecté
        if (isset($_SESSION['userId'])) {
            // Alors on récupère cet utilisateur
            $currentUser = $_SESSION['userObject'];
            // On récupère son rôle
            $currentUserRole = $currentUser->getRole();
            // Si le rôle fait partie des rôles autorisés (fournis en paramètre)
            if (in_array($currentUserRole, $roles)) {
                // Alors on return true
                return true;
            }
            else {
                // Sinon, on affiche une erreur 403
                $error = new ErrorController();
                $error->err403();
                // Et on arrête tout...
                exit;

            }
        }
        // Sinon (l'utilisateur n'est pas connecté)
        else {
            $loginPageUrl = $router->generate('user-login');
            // On redirige vers la page de login
            header('Location: ' . $loginPageUrl);
            exit;
        }
    }
}
