<?php //var_dump($product->getName()); ?>

<a href="<?= $router->generate('current-user') ?>" class="btn btn-success float-right">Retour</a>
<?php // le traitement ci dessous peut-être délégué à une méthode... ?>
<h2><?php if ($user->getEmail() === null) : ?>Ajouter<?php else : ?>Modifier<?php endif; ?> un utilisateur</h2>
        
        <form action="" method="POST" class="mt-5">
            <div class="form-group">
                <label for="email">Email</label>
                <input name="email" type="text" class="form-control" id="email" placeholder="Votre email" value="<?= $user->getEmail(); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input name="password" type="text" class="form-control" id="password" placeholder="Votre mot de passe" value="<?= $user->getPassword(); ?>">
            </div>
            <div class="form-group">
                <label for="subtitle">Prénom</label>
                <input name="firstname" type="text" class="form-control" id="firstname" placeholder="firstname" aria-describedby="subtitleHelpBlock" value="<?= $user->getFirstname(); ?>">
                <small id="subtitleHelpBlock" class="form-text text-muted">
                    Sera affiché sur la page d'accueil comme bouton devant l'image
                </small>
            </div>
            <div class="form-group">
                <label for="lastname">Nom de famille</label>
                <input name="lastname" type="text" class="form-control" id="lastname" placeholder="Votre nom de famille" value="<?= $user->getLastname(); ?>">
            </div>
            <?php // if ($user->getEmail() === null) : ?>
            <div class="form-group">
                <label for="role-select">choissez le role</label>
                <select name="role" type="text" class="form-control" id="role-select" value="<?= $user->getRole(); ?>">
                    <option value="">--Choissez une option--</option>
                    <option value="admin">admin</option>
                    <option value="catalog-manager">catalog-manager</option>
                </select> 
            </div>
            <div class="form-group">
                <label for="status-select">choissez le status</label>
                <select name="status" type="text" class="form-control" id="status-select" value="<?= $user->getStatus(); ?>">
                    <option value="">--Choissez une option--</option>
                    <option value="1">actif</option>
                    <option value="2">désactivé</option>
                </select> 
            </div>
            <?php /*else : ?>
                <div class="form-group">
                <label for="role-select">choissez le role</label>
                <select name="role" type="text" class="form-control" id="role-select" value="<?= $user->getRole(); ?>">
                    <option value="">--Choissez une option--</option>
                    <option value="admin">admin</option>
                    <option value="catalog-manager">catalog-manager</option>
                </select> 
            </div>
            <div class="form-group">
                <label for="status-select">choissez le status</label>
                <select name="status" type="text" class="form-control" id="status-select" value="<?= $user->getStatus(); ?>">
                    <option value="">--Choissez une option--</option>
                    <option value="1">actif</option>
                    <option value="2">désactivé</option>
                </select> 
            </div>
            <?php //endif; */?>
            <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
        </form>