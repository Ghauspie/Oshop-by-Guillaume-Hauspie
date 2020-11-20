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
    //Pour ajouter la nouvelle category
    public function create()
    {  
        dump($_POST);
        $name=filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        $subtitle=filter_input(INPUT_POST,'subtitle',FILTER_SANITIZE_STRING);
        $picture=filter_input(INPUT_POST,'picture',FILTER_VALIDATE_URL);
        

         // var_dump($name, $description, $picture);

        // On valide nos données (et oui, c'est important de le faire aussi côté serveur)

        // on prépare un array qui va contenir nos erreurs
        $errorsList = [];

        // si le champ name est vide, on alimente l'array d'erreurs
        if (empty($name)) {
            $errorsList[] = 'Le nom est vide';
        }

        if (empty($subtitle)) {
            $errorsList[] = 'La description est vide';
        }
        if ($picture === false) {
            $errorsList[] = 'L\'URL de l\'image est invalide';
        }
        //echo $name_db;
        //var_dump($errorsList);
           // Je n'ai aucune erreur, OK, GO BDD
           if (empty($errorsList)) {
            echo 'On y va, on enregistre les données en BDD';

            // On instancie un nouveau modèle de type  Category
            $category = new Category();
            // Pour le moment il est vide
            //var_dump($category);

            // On met à jour les propriétés de l'instance (grace aux setters)
            $category->setName($name);
            $category->setSubtitle($subtitle);
            $category->setPicture($picture);

            // mon objet est "rempli"
            // On appelle la méthode qui permet d'insérer les données en BDD
            $category->insert();
            // si insert() return true, tout s'est bien passé (enregistre)
            if($category->insert()) {
                header('Location: /category/list');
                exit;
            }
            else {
                echo 'KO, souci avec ajout en BDD';
            }

        }

        // oups ! j'ai des erreurs dans mon array...
        else {
            echo 'Coco ! t\'as des erreurs';        }
        
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
    
}
