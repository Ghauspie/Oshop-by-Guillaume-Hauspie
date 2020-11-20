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
        $test=new Category;
        $testcreate=$test->createCategory();
        $this->show('category/add-edit',[
            'category' => new Category(),
            'category_create'=> $testcreate
        ]);
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
