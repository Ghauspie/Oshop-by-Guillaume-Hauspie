<?php // TODO : gérer l'afichage des erreurs (UI)

if(isset($errorsList)){
    var_dump($errorsList);
}
?>

<a href="<?= $router->generate('product-list') ?>" class="btn btn-success float-right">Retour</a>
<?php // le traitement ci dessous peut-être délégué à une méthode... ?>
<h2><?php if ($product->getName() === null) : ?>Ajouter<?php else : ?>Modifier<?php endif; ?> un produit</h2>
        
        <form action="" method="POST" class="mt-5">
            <div class="form-group">
                <label for="name">Nom</label>
                <input name="name" type="text" class="form-control" id="name" placeholder="Nom de la catégorie" value="<?= $product->getName(); ?>">
            </div>
            <div class="form-group">
                <label for="subtitle">Description</label>
                <input name="description" type="text" class="form-control" id="subtitle" placeholder="Description" aria-describedby="subtitleHelpBlock" value="<?= $product->getDescription(); ?>">
                <small id="subtitleHelpBlock" class="form-text text-muted">
                    Sera affiché sur la page d'accueil comme bouton devant l'image
                </small>
            </div>
            <div class="form-group">
                <label for="picture">Image</label>
                <input name="picture" type="text" class="form-control" id="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock" value="<?= $product->getPicture(); ?>">
                <small id="pictureHelpBlock" class="form-text text-muted">
                    URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
                </small>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
        </form>