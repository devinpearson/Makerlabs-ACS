<?php
/**
 * Description of User
 *
 * @author devin
 */
class User {

    private $db;
    private $id;
    private $name;
    private $email;
    private $password;
    private $type;
    private $enable;
    private $date;
    private $accessLevel;

    function __construct($db) {
        //parent::__construct();
        $this->db = $db;
    }
    
    function create($id, $name, $email, $password, $type, $enable, $date, $accessLevel) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->type = $type;
        $this->enable = $enable;
        $this->date = $date;
        $this->accessLevel = $accessLevel;
    }
    
    function load($id) {
        $this->id = $id;

        try {
            $statement = $this->db->prepare("SELECT * FROM `user` WHERE id = :id LIMIT 1");
            $statement->execute(array(':id' => $this->id));
            if (($row = $statement->fetch()) != false) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                $this->type = $row['type'];
                $this->enable = $row['enable'];
                $this->date = $row['date'];
                $this->accessLevel = $row['access_level'];
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            //ErrorLog::log($e->getMessage(), __DIR__, __CLASS__, __FUNCTION__, $this->code, $e->getLine());
            return false;
        }
    }

    function loadByEmail($email) {
        $this->email = $email;

        try {
            $statement = $this->db->prepare("SELECT * FROM `user` WHERE email = :email LIMIT 1");
            $statement->execute(array(':email' => $this->email));
            if (($row = $statement->fetch()) != false) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                $this->type = $row['type'];
                $this->enable = $row['enable'];
                $this->date = $row['date'];
                $this->accessLevel = $row['access_level'];
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            //ErrorLog::log($e->getMessage(), __DIR__, __CLASS__, __FUNCTION__, $this->code, $e->getLine());
            return false;
        }
    }

    function save() {
        try {
            $statement = $this->db->prepare("INSERT INTO `user` (`name`, `email`, `password`, `type`, `enable`, `date`, `access_level`) VALUES (:name, :email, :password, :type, :enable, :date, :accesslevel) ON DUPLICATE KEY UPDATE `name` = :name, `email` = :email, `password` = :password, `type` = :type, `enable` = :enable, `date` = :date, `access_level` = :accesslevel");
            $statement->execute(array(':name' => $this->name, ':email' => $this->email, ':password' => $this->password, ':type' => $this->type, ':enable' => $this->enable, ':date' => $this->date, ':accesslevel' => $this->accessLevel));
            $this->id = $this->db->lastInsertId();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            //ErrorLog::log($e->getMessage(), __DIR__, __CLASS__, __FUNCTION__, 16, $e->getLine());
            return false;
        }
    }
    
    function asArray() {
        $result = array();
        $result['id'] = $this->id;
        $result['name'] = $this->name;
        $result['email'] = $this->email;
        $result['password'] = $this->password;
        $result['type'] = $this->type;
        $result['enable'] = $this->enable;
        $result['accessLevel'] = $this->accessLevel;
        $result['date'] = $this->date;
        return $result;
    }
    
    function getId() {
        return $this->id;
    }
    
    function getName() {
        return $this->name;
    }
    
    function getEmail() {
        return $this->email;
    }
    
    function getPassword() {
        return $this->password;
    }
    
    function getType() {
        return $this->type;
    }
    
    function getEnable() {
        return $this->enable;
    }
    
    function getDate() {
        return $this->date;
    }
    
    function getAccessLevel() {
        return $this->accessLevel;
    }
    
    function setName($name) {
        $this->name = $name;
    }
    
    function setEmail($email) {
        $this->email = $email;
    }
    
    function setPassword($password) {
        $this->password = $password;
    }
    
    function setType($type) {
        $this->type = $type;
    }
    
    function setEnable($enable) {
        $this->enable = $enable;
    }
    
    function setDate($date) {
        $this->date = $date;
    }
    
    function setAccessLevel($accessLevel) {
        $this->accessLevel = $accessLevel;
    }
}