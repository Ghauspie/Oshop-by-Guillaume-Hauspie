
        <a href="product_add.html" class="btn btn-success float-right">Ajouter</a>
        
        <h2>Liste des produits</h2>
        <table class="table table-hover mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Description</th>
                    <th scope="col">Picture</th>
                    <th scope="col">Price</th>
                    <th scope="col">Rate</th>
                    <th scope="col">Status</th>
                    <th scope="col">Brand_name</th>
                    <th scope="col">Category_name</th>
                    <th scope="col">Type_name</th>
                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($viewVars['product_list'] as $productValue) :?>
                <tr>
                    <th scope="row"><?= $productValue['id'] ?></th>
                    <td><?= $productValue['name'] ?></td>
                    <td><?= $productValue['description'] ?><r</td>
                    <td><img src="./<?= $productValue['picture'] ?>" alt="<?= $productValue['picture'] ?>"></td>
                    <td><?= $productValue['price']?></td>
                    <td><?= $productValue['rate']?></td>
                    <td><?= $productValue['status']?></td>
                    <td><?= $productValue['brand_name'] ?></td>
                    <td><?= $productValue['category_name'] ?></td>
                    <td><?= $productValue['name_type'] ?></td>

                    <td class="text-right">
                        <a href="" class="btn btn-sm btn-warning">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <!-- Example single danger button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-danger dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Oui, je veux supprimer</a>
                                <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
                            </div>
                        </div>
                    </td>
                </tr>
               <?php endforeach ?>
            </tbody>
        </table>
   