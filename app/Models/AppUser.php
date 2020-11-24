<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class AppUser extends CoreModel {

    protected $email;
    protected $password;
    protected $firstname;
    protected $lastname;
    protected $role;
    protected $status;

    

    static public function findAll(){
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `app_user`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\AppUser');
        
        return $results;
    }

    public static function find($userid){{
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `category` WHERE `id` =' . $userid;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $user = $pdoStatement->fetchObject('App\Models\AppUser');

        // retourner le résultat
        return $user;
    }}

    public function delete(){}
   
    public function update(){
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

    public function insert(){
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO
        $sql =$pdo->prepare("
            INSERT INTO `app_user`(email,password,firstname,lastname,role,status)
            VALUES (':email', ':password', ':firstname', ':lastname',':role', ':status')
        ");

        // Préparation de la requête d'insertion (+ sécurisé que exec directement)
        // @see https://www.php.net/manual/fr/pdo.prepared-statements.php
        //
        // Permet de lutter contre les injections SQL
        // @see https://portswigger.net/web-security/sql-injection (exemples avec SELECT)
        // @see https://stackoverflow.com/questions/681583/sql-injection-on-insert (exemples avec INSERT INTO)
        

        // Execution de la requête d'insertion
        // On peut envoyer les données « brutes » à execute() qui va les "sanitize" pour SQL.
        $sql->bindValue(':email', $this->email,PDO::PARAM_STR);
        $sql->bindValue(':password', $this->password,PDO::PARAM_STR);
        $sql->bindValue(':firstname', $this->firstname,PDO::PARAM_STR);
        $sql->bindValue(':lastname', $this->lastname,PDO::PARAM_STR);
        $sql->bindValue(':role', $this->role,PDO::PARAM_STR);
        $sql->bindValue(':status', $this->status,PDO::PARAM_STR);

          // Execution de la requête d'insertion (exec, pas query)
          $insertedRows = $sql->execute();

          // Si au moins une ligne ajoutée
          if ($insertedRows > 0) {
              // Alors on récupère l'id auto-incrémenté généré par MySQL
              $this->id = $pdo->lastInsertId();
  
              // On retourne VRAI car l'ajout a parfaitement fonctionné
              return true;
              // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
          }
          
          // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
          return false;

    }

    /**
     * Get the value of name
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public static function findByEmail($email){
         // se connecter à la BDD
         $pdo = Database::getPDO();

         $sql='
         SELECT *
         FROM `app_user`
         WHERE `email` = :email'
         ;
         
        $pdoStatement=$pdo->prepare($sql);
        $pdoStatement->bindValue(':email',$email,PDO::PARAM_STR);
        $pdoStatement->execute();
        $Login = $pdoStatement->fetchObject(self::class);
        
       return $Login;
    }

    public function connected(){

         // se connecter à la BDD
         $pdo = Database::getPDO();

         //créer la requete sql permettant de verifier que l'email et le password sont correct
         $sql=$pdo->prepare("
         SELECT * FROM 'app_user'
         WHERE 'email'=':email' and 'password'=':password'        
         "
         );


        $sql->bindValue(':email', $this->email, PDO::PARAM_STR);
        $sql->bindValue(':password', $this->password, PDO::PARAM_STR);
         // Execution de la requête d'insertion
         // On peut envoyer les données « brutes » à execute() qui va les "sanitize" pour SQL.
        $sql->execute();


         // Si au moins une ligne ajoutée
        if ($sql->rowCount() > 0) {
            

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }
        
        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }
}