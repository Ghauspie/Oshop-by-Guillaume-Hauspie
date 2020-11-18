<?php

namespace App\Controllers;

use App\Models\{Category, Product, Type, Brand};



class CatalogController extends CoreController {

 public function categoryAction() {
     $categoryObject= new Category;
     $categoryList=$categoryObject->findAll();
     $this->show('main/category',[
         'category_list' => $categoryList
     ]);
 }

 public function productAction() {
     $productObject = new Product;
     $productList = $productObject->findAll();
     $this->show('main/product', [
         'product_list' => $productList
     ]);
 }

 public function typeAction() {
    $typeObject= new Type;
    $typeList=$typeObject->findAll();
    $this->show('main/type',[
        'type_list' => $typeList
    ]);
}

public function brandAction() {
    $brandObject= new Brand;
    $brandList=$brandObject->findAll();
    $this->show('main/brand',[
        'brand_list' => $brandList
    ]);
}

public function categoryAddAction() {
    $this->show('main/categories_add');
}
}