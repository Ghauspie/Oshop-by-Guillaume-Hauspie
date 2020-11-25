<?php

namespace App\Controllers;

use App\Models\Product;

/**
 * Controller dédié à l'affichage des produits
 * 
 */
class ProductController extends CoreController {
    /**
     * Listing des produits
     * 
     * @return void
     */
    public function list()
    {
        // On récupère tous les produits
        $productModel = new Product();
        $products = $productModel->findAll();
        // var_dump($products);

        // On les envoie à la vue
        $this->show(
            'product/list',
            ['products' => $products]
        );
    }

    /**
     * Ajout d'un produit
     * 
     * @return void
     */
    public function add()
    {
        $this->show(
            'product/add-edit',
            [
                'product' => new Product()
            ]
        );
    }

    /**
     * Edition d'un produit
     * 
     * @param int $productId L'ID du produit à éditer
     */
    public function edit($productId)
    {
        // On récupère notre produit
        $productModel = new Product();
        $product = $productModel->find($productId);

        // On affiche notre vue en transmettant les infos du produit
        $this->show(
            'product/add-edit',
            [
                'product' => $product
            ]
        );
    }
    /**
     * Création d'un produit (POST)
     *
     * @return void
     */
    public function create() {
        // var_dump($_POST);

        // on récupère les données issues du formulaire
        // on utiliser filter_input(https://www.php.net/manual/fr/function.filter-input.php) pour faciliter le traitement des données soumises (filtres, sécurité, etc...)
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);
        

        // var_dump($name, $description, $picture);

        // On valide nos données (et oui, c'est important de le faire aussi côté serveur)

        // on prépare un array qui va contenir nos erreurs
        $errorsList = [];

        // si le champ name est vide, on alimente l'array d'erreurs
        if (empty($name)) {
            $errorsList[] = 'Le nom est vide';
        }

        if (empty($description)) {
            $errorsList[] = 'La description est vide';
        }

        // si l'url n'est pas valide (vide ou mal formatée)
        if ($picture === false) {
            $errorsList[] = 'L\'URL de l\'image est invalide';
        }

        // echo $name;
        // var_dump($errorsList);

        // Je n'ai aucune erreur, OK, GO BDD
        if (empty($errorsList)) {
            echo 'On y va, on enregistre les données en BDD';

            // On instancie un nouveau modèle de type Product
            $product = new Product();

            // pour le moment, il est vide...
            // var_dump($product);

            // On met à jour les propriétés de l'instance (grâce aux setters)
            // => les setters permettent de mettre à jour les propriétés
            $product->setName($name);
            $product->setDescription($description);
            $product->setPicture($picture);

            // rustine (en attentant)
            // TODO : gérer ça plus proprement (dans le form)
            $product->setTypeId(5);
            $product->setBrandId(5);

            // mon objet est "rempli"
            // var_dump($product);
            // les getters permettent de lire les propriétés
            // echo '<br>' . $product->getName();

            // On appelle la méthode qui permet d'insérer les données en BDD
            // si insert() return true, tout s'est bien passé (enregistrement en BDD)
            if($product->insert()) {
                // on redirige l'utilisateur vers la liste des produits
                header('Location: /product/list');
                exit;
            }
            // sinon, (insert() return false) on a un souci
            else {
                echo 'KO, souci avec ajout en BDD';
            }

        }

        // oups ! j'ai des erreurs dans mon array...
        else {
            // echo 'Coco ! t\'as des erreurs';
            // var_dump($errorsList);

            $product = new Product();

            $product->setName(filter_input(INPUT_POST, 'name'));
            $product->setDescription(filter_input(INPUT_POST, 'description'));
            $product->setPicture(filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL));

            $this->show(
                'product/add-edit',
                [
                    // on pré rempli avec les infos saisies précédement
                    'product' => $product,
                    // on transmet aussi l'array d'erreurs
                    'errorsList' => $errorsList
                ]
            );
        }
    }

    /**
     * POST Modification d'un produit(dans la BDD).
     *
     * @return void
     */
    public function update($productId)
    {


        // On tente de récupèrer les données venant du formulaire.
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);

        // On vérifie l'existence et la validité de ces données (gestion d'erreur).

        $errorsList = [];
        // si le champ name est vide, on alimente l'array d'erreurs
        if (empty($name)) {
            $errorsList[] = 'Le nom est vide';
        }

        if (empty($description)) {
            $errorsList[] = 'La description est vide';
        }

        // si l'url n'est pas valide (vide ou mal formatée)
        if ($picture === false) {
            $errorsList[] = 'L\'URL de l\'image est invalide';
        }

        // S'il n'y a aucune erreur dans les données...
        if (empty($errorsList)) {

            $product = Product::find($productId);

            // On met à jour les propriétés de l'instance.
            $product->setName($name);
            $product->setDescription($description);
            $product->setPicture($picture);

            // On execute la méthode update sur le modèle
            $ok = $product->update();

            // On tente de sauvegarder les données en DB...
            if ($ok) {
                // Si la sauvegarde a fonctionné, on redirige vers la liste des catégories.
                header('Location: /product/list');
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
            // echo 'Coco ! t\'as des erreurs';
            // var_dump($errorsList);

            $product = new Product();

            $product->setName(filter_input(INPUT_POST, 'name'));
            $product->setDescription(filter_input(INPUT_POST, 'description'));
            $product->setPicture(filter_input(INPUT_POST, 'picture'));

            $this->show(
                'product/add-edit',
                [
                    // on pré rempli avec les infos saisies précédement
                    'product' => $product,
                    // on transmet aussi l'array d'erreurs
                    'errorsList' => $errorsList
                ]
            );
        }
    }
}