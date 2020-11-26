<?php

namespace App\Controllers;

use App\Models\Category;


// Pour interdire l'instanciation de CoreModel, on rend la classe abstraite (avec abstract)
// use App\Models\CoreModel;
// $coreModelObject = new CoreModel();
// var_dump($coreModelObject);

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
            [
                'categories' => $categories,
                'tokenCSRF' => $this->generateTokenCSRF()
            ]
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
     * Affiche la vue édition d'une catégorie (pré-remplie)
     * 
     * @param int $categoryId L'ID de la catégorie à éditer
     */
    public function edit($categoryId)
    {
        // On récupère notre catégorie
        $category = Category::find($categoryId);

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
        $errorsList = [];

        // Pour le "name", faut vérifier si la chaîne est présente *et* si elle
        // a passé le filtre de validation.
        if (empty($name)) {
            $errorsList[] = 'Le nom est vide';
        }
        if ($name === false) {
            $errorsList[] = 'Le nom est invalide';
        }
        // Pareil pour le "subtitle".
        if (empty($subtitle)) {
            $errorsList[] = 'Le sous-titre est vide';
        }
        if ($subtitle === false) {
            $errorsList[] = 'Le sous-titre est invalide';
        }
        // Pour l'URL de l'image "picture", le filtre vérifie forcément sa présence aussi.
        if ($picture === false) {
            $errorsList[] = 'L\'URL d\'image est invalide';
        }

        // S'il n'y a aucune erreur dans les données...
        if (empty($errorsList)) {
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
                    'errorsList' => $errorsList
                ]
            );
        }
    }

     /**
     * POST Modification d'une catégorie(dans la BDD).
     *
     * @return void
     */
    public function update($categoryId)
    {

        // var_dump($categoryId);
        // exit;

        // On tente de récupèrer les données venant du formulaire.
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);

        // On vérifie l'existence et la validité de ces données (gestion d'erreur).
        $errorsList = [];

        // Pour le "name", faut vérifier si la chaîne est présente *et* si elle
        // a passé le filtre de validation.
        if (empty($name)) {
            $errorsList[] = 'Le nom est vide';
        }
        if ($name === false) {
            $errorsList[] = 'Le nom est invalide';
        }
        // Pareil pour le "subtitle".
        if (empty($subtitle)) {
            $errorsList[] = 'Le sous-titre est vide';
        }
        if ($subtitle === false) {
            $errorsList[] = 'Le sous-titre est invalide';
        }
        // Pour l'URL de l'image "picture", le filtre vérifie forcément sa présence aussi.
        if ($picture === false) {
            $errorsList[] = 'L\'URL d\'image est invalide';
        }

        // S'il n'y a aucune erreur dans les données...
        if (empty($errorsList)) {

            // 1. On récupère la catégorie concernée dans la BDD => on récupère un objet
            // 2. On alimente cet objet avec les données mises à jour
            // 3. On met à jour dans la BDD

            // On récupère la catégorie courante (dans la BDD)
            $category = Category::find($categoryId);

            // var_dump($category);
            // die;

            // On met à jour les propriétés de l'instance.
            $category->setName($name);
            $category->setSubtitle($subtitle);
            $category->setPicture($picture);

            // On execute la méthode update sur le modèle
            $ok = $category->update();

            // On tente de sauvegarder les données en DB...
            if ($ok) {
                // Si la sauvegarde a fonctionné, on redirige vers la liste des catégories.
                header('Location: /category/list');
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
                    'errorsList' => $errorsList
                ]
            );
        }
    }

    public function delete($categoryId)
    {
        $category = Category::find($categoryId);

        // si la catégorie existe
        if($category) {
            // on la supprime de la BDD
            $category->delete();
            // on redirige vers la liste
            header('Location: /category/list');
        }
        else {
            echo 'La catégorie n\'existe pas';
        }
    }

    /**
     * Affiche le formulaire de sélection des catégories présentes sur la home
     *
     * @return void
     */
    public function getHomeSelection()
    {
        $categories = Category::findAll();

        if ($categories) {
            $this->show(
                'category/home-selection',
                [
                    'categories' => $categories,
                    'tokenCSRF' => $this->generateTokenCSRF()
                ]
            );
        }
    }

    /**
     * Modification de l'ordre des catégories sur la home (depuis le POST du form)
     *
     * @return void
     */
    public function setHomeSelection()
    {

        global $router;

        // 1. Récupération des infos du form
        // 2. Validation des données
        // 3. Si OK, sauvegarde en BDD
        // 4. Si pas OK, affichage d'erreur

        // On récupère un array qui va contenir l'emplacement des catégories
        // var_dump($_POST);
        $spots = $_POST['emplacement'];
        // var_dump($spots);

        $errorsList = [];

        if (in_array('', $spots)) {
            $errorsList[] = 'Il faut sélectionner une catégorie pour chaque emplacement.';
        }
        
        // ci-dessous, le tableau dédoublonné
        // dump(array_unique($spots));

        // ci-dessous, le nombre d'items dans le tableau dédoublonné
        // dump(count(array_unique($spots)));

        // ci-dessous, le nombre d'items dans le tableau (non dédoublonné)
        // dump(count($spots));

        if (count(array_unique($spots)) !== count($spots)) {
            $errorsList[] = 'Une catégorie à la fois !';
        }

        // éventuellement d'autres vérifs...quoi qu'un peu overkill

        // dump($errorsList);

        // si j'ai une ou des erreur(s)
        if (!empty($errorsList)) {
            $categories = Category::findAll();

            $this->show('category/home-selection',
                [
                    'categories' => $categories,
                    'errors' => $errorsList,
                    'tokenCSRF' => $this->generateTokenCSRF(),
                    'spots' => $spots 
                ]
                );
        }
        // sinon
        else {
            $ok = Category::setHomeSelection($spots);

            // var_dump($ok);

            if ($ok) {
                $location = $router->generate('main-selections');
                header("Location: $location");
            }
            else {
                $errorsList[] = 'La sauvegarde a échoué !';
                var_dump($errorsList);
                $categories = Category::findAll();
                $this->show('category/home-selection', [
                    'categories' => $categories,
                    'errorsList' => $errorsList,
                    'tokenCSRF' => $this->generateTokenCSRF()
                ]);
            }
        }
    }
}