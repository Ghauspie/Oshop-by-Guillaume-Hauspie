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

    

    static public function findAll(){}

    public static function find($id){}

    public function delete(){}
   
    public function update(){}

    public function insert(){}

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

         $sql=
            "SELECT * FROM `app_user`
        WHERE `email`='$email'"
         ;
         
       
        $pdoStatement=$pdo->query($sql);

        $Login = $pdoStatement->fetchObject('App\Models\AppUser');
        
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