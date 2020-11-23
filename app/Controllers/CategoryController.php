<?php

namespace App\Controllers;

use App\Models\Category;

/**
 * Controller dédié à l'affichage des catégories
 */
class CategoryController extends CoreController {
    /**
     * Listing des categories
     */
    public function list()
    {
        // On récupère toutes les catégories
        $categoryModel = new Category();
        $categories = $categoryModel->findAll();
        // var_dump($products);

        // On les envoie à la vue
        $this->show(
            'category/list',
            ['categories' => $categories]
        );
    }

    /**
     * Ajout d'une catégorie
     * 
     * @return void
     */
    public function add()
    {
        $this->show(
            'category/add-edit',
            [
                'category' => new Category()
            ]
        );
    }    

    /**
     * Edition d'une catégorie
     * 
     * @param int $categoryId L'ID de la catégorie à éditer
     */
    public function update($categoryId)
    {
        // On récupère notre catégorie
        $categoryModel = new Category();
        $category = $categoryModel->find($categoryId);

        // On affiche notre vue en transmettant les infos du produit
        $this->show(
            'category/add-edit',
            [
                'category' => $category
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
        // On tente de récupèrer les données venant du formulaire.
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);

        // On vérifie l'existence et la validité de ces données (gestion d'erreur).
        $errorList = [];

        // Pour le "name", faut vérifier si la chaîne est présente *et* si elle
        // a passé le filtre de validation.
        if (empty($name)) {
            $errorList[] = 'Le nom est vide';
        }
        if ($name === false) {
            $errorList[] = 'Le nom est invalide';
        }
        // Pareil pour le "subtitle".
        if (empty($subtitle)) {
            $errorList[] = 'Le sous-titre est vide';
        }
        if ($subtitle === false) {
            $errorList[] = 'Le sous-titre est invalide';
        }
        // Pour l'URL de l'image "picture", le filtre vérifie forcément sa présence aussi.
        if ($picture === false) {
            $errorList[] = 'L\'URL d\'image est invalide';
        }

        // S'il n'y a aucune erreur dans les données...
        if (empty($errorList)) {
            // On instancie un nouveau modèle de type Category.
            $category = new Category();

            // On met à jour les propriétés de l'instance.
            $category->setName($name);
            $category->setSubtitle($subtitle);
            $category->setPicture($picture);

            // On tente de sauvegarder les données en DB...
            if ($category->insert()) {
                // Si la sauvegarde a fonctionné, on redirige vers la liste des catégories.
                header('Location: /category/list');
                exit;
            } else {
                // Sinon, on ajoute un message d'erreur à la page actuelle, et on laisse
                // l'utilisateur retenter la création.
                $errorList[] = 'La sauvegarde a échoué';
            }
        }

        // S'il y a une ou de(s) erreur(s) dans les données...
        else {
            // On réaffiche le formulaire, mais pré-rempli avec les (mauvaises) données
            // proposées par l'utilisateur.
            // Pour ce faire, on instancie un modèle Category, qu'on passe au template.

            $category = new Category();
            $category->setName(filter_input(INPUT_POST, 'name'));
            $category->setSubtitle(filter_input(INPUT_POST, 'subtitle'));
            $category->setPicture(filter_input(INPUT_POST, 'picture'));

            $this->show(
                'category/add-edit',
                [
                    // On pré-remplit les inputs avec les données BRUTES initialement
                    // reçues en POST, qui sont actuellement stockées dans le modèle.
                    'category' => $category,
                    // On transmet aussi le tableau d'erreurs, pour avertir l'utilisateur.
                    'errorList' => $errorList
                ]
            );
        }
    }
    /**
     * POST Création d'une catégorie.
     *
     * @return void
     */
    public function updateBDD(){
        // On uttilse global $router car on à stocké la racine du site dedans
        global $router;
         // On tente de récupèrer les données venant du formulaire.
         $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
         $subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_STRING);
         $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);
         $id=filter_input(INPUT_POST, 'id');

     // On instancie un nouveau modèle de type Category.
     $category = new Category();
 
    // On met à jour les propriétés de l'instance.
    $category->setName($name);
    $category->setSubtitle($subtitle);
    $category->setPicture($picture);
    $category->setId($id);

 
             // On tente de sauvegarder les données en DB...
             if ($category->update()=== true) 
             {
                 // Si la sauvegarde a fonctionné, on redirige vers la liste des catégories.
                 header('Location: /category/list');
                 exit;
             }
             else {
                 // Sinon, on ajoute un message d'erreur à la page actuelle, et on laisse
                 // l'utilisateur retenter la création.
                 echo 'La sauvegarde a échoué';
                   }
    }
}