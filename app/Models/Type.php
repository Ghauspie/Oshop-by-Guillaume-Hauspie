<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Un modèle représente une table (un entité) dans notre base
 * 
 * Un objet issu de cette classe réprésente un enregistrement dans cette table
 */
class Type extends CoreModel {
    // Les propriétés représentent les champs
    // Attention il faut que les propriétés aient le même nom (précisément) que les colonnes de la table
    
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $footer_order;

    /**
     * Méthode permettant de récupérer un enregistrement de la table Type en fonction d'un id donné
     * 
     * @param int $typeId ID du type
     * @return Type
     */
    public static function find($typeId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `type` WHERE `id` =' . $typeId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $type = $pdoStatement->fetchObject('App\Models\Type');

        // retourner le résultat
        return $type;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table type
     * 
     * @return Type[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `type`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Type');
        
        return $results;
    }

    /**
     * Récupérer les 5 types mis en avant dans le footer
     * 
     * @return Type[]
     */
    public function findAllFooter()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM type
            WHERE footer_order > 0
            ORDER BY footer_order ASC
        ';
        $pdoStatement = $pdo->query($sql);
        $types = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Type');
        
        return $types;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */ 
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of footer_order
     *
     * @return  int
     */ 
    public function getFooterOrder()
    {
        return $this->footer_order;
    }

    /**
     * Set the value of footer_order
     *
     * @param  int  $footer_order
     */ 
    public function setFooterOrder(int $footer_order)
    {
        $this->footer_order = $footer_order;
    }

     /**
     * Méthode permettant d'ajouter un enregistrement dans la table category.
     * L'objet courant doit contenir toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     * 
     * @return bool
     */
    public function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO
        $sql = "
            INSERT INTO `type` (name, subtitle, picture)
            VALUES (:name, :subtitle, :picture)
        ";

        // Préparation de la requête d'insertion (+ sécurisé que exec directement)
        // @see https://www.php.net/manual/fr/pdo.prepared-statements.php
        //
        // Permet de lutter contre les injections SQL
        // @see https://portswigger.net/web-security/sql-injection (exemples avec SELECT)
        // @see https://stackoverflow.com/questions/681583/sql-injection-on-insert (exemples avec INSERT INTO)
        $query = $pdo->prepare($sql);

        // Execution de la requête d'insertion
        // On peut envoyer les données « brutes » à execute() qui va les "sanitize" pour SQL.
        $query->execute([
          ':name' => $this->name,
          ':subtitle' => $this->subtitle,
          ':picture' => $this->picture,
        ]);

        // Si au moins une ligne ajoutée
        if ($query->rowCount() > 0) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }
        
        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }

    //on créer la function pour faire les modification d'une category
    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = $pdo->prepare("
            UPDATE `category`
            SET
                name = :name,
                subtitle = :subtitle,
                picture = :picture,
                updated_at = NOW()
            WHERE id = :id
        ");

        //$pdoStatement = $pdo->prepare($sql);

        // on utilise bindValue et pas simplement un array
        // avantage : on peut contraindre les types de données
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        $sql->bindValue(':name', $this->name, PDO::PARAM_STR);
        $sql->bindValue(':subtitle', $this->subtitle, PDO::PARAM_STR);
        $sql->bindValue(':picture', $this->picture, PDO::PARAM_STR);

        // Execution de la requête de mise à jour
       return $sql->execute();
    }

     /**
     * Méthode de supression d'une catégorie 
     *
     * @return bool Retourne 'True' si la catégorie a bien été supprimée
     */
    public function delete(){
        $pdo= Database::getPDO();
    
        $sql= "DELETE FROM `category` WHERE id =:id";

        $pdoStatement =$pdo->prepare($sql);

        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);

        $pdoStatement->execute();
        
        return ($pdoStatement->rowCount()>0);
    }

}
