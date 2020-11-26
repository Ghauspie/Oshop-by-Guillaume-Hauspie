
     <h2>Liste des sélections</h2>
        <table class="table table-hover mt-4">
            <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <a href="<?= $router->generate('category-get_home_selection') ?>">Catégories de la home</a>
                    </td>
                    <td class="text-right">
                        <a href="<?= $router->generate('category-get_home_selection') ?>" class="btn btn-sm btn-warning">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>