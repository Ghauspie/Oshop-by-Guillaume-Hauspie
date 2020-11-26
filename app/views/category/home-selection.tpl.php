<a href="<?= $router->generate('main-selections') ?>" class="btn btn-success float-right">Retour</a>
<h2>Sélection des catégories de la page d'accueil</h2>

<form action="" method="POST" class="mt-5">
    <input type="hidden" name="tokenCSRF" value="<?= $tokenCSRF ?>">
    <?php for ($i = 1; $i <= 5; $i++) : ?>
        <?php if ($i === 1 || $i === 3) {
            echo '<div class="row">';
        }
        ?>
        <div class="col">
            <div class="form-group">
                <label for="emplacement<?= $i ?>">Emplacement #<?= $i ?></label>
                <select class="form-control" id="emplacement<?= $i ?>" name="emplacement[]">
                    <option value="">choisissez :</option>
                    <?php foreach ($categories as $category) : ?>
                    
                    <?php if (isset($spots)) : // si on accède au form après une ou plusieures soumission (erreurs)
                    ?>

                        <option <?php if ($category->getId() == $spots[$i - 1]) : ?>selected <?php endif; ?>value="<?= $category->getId() ?>"><?= $category->getName() ?></option>

                    <?php else :
                    
                    ?>

                        <option <?php if ($category->getHomeOrder() == $i) : ?>selected <?php endif; ?>value="<?= $category->getId() ?>"><?= $category->getName() ?></option>

                    <?php endif; ?>

                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php if ($i === 2 || $i === 5) {
            echo '</div>';
        }
        ?>
    <?php endfor; ?>

    <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
</form>