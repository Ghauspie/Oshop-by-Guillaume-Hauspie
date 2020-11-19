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
    public function update($productId)
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
}