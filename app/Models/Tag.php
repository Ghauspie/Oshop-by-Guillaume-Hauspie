<?php

namespace App\Models;

use App\Utils\Database;
use PDO;
use Symfony\Component\VarDumper\Cloner\Data;

/**
 * Modèle pour les tags
 */
class Tag extends CoreModel
{

    /**
     * Nom du tag
     *
     * @var string
     */
    private $name;

    // Pour remplir le "contrat" de abstract (CoreModel)
    // il me faut obligatoirement les méthodes suivantes

    /**
     * Retourne tous les tags
     *
     * @return Tag[] Retourne un array de tags
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `tag`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        return $results;
    }

    public static function find($id)
    {
        
    }

    public function update()
    {
        
    }

    public function delete()
    {
        
    }

    public function insert()
    {
        
    }

    /**
     * Get nom du tag
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nom du tag
     *
     * @param  string  $name  Nom du tag
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}