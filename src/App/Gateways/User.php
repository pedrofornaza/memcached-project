<?php

namespace App\Gateways;

use App\Entities\User as Entity;

class User
{
    protected $conn;
    protected $cache;

    public function __construct(\PDO $conn, $cache = null)
    {
        $this->conn = $conn;
        $this->cache = $cache;
    }

    public function add(Entity $entity)
    {
        $query = 'INSERT INTO users (name, email, pass) VALUES (:name, :email, :pass) RETURNING id';
        $stm = $this->conn->prepare($query);

        $stm->bindValue(':name', $entity->getName());
        $stm->bindValue(':email', $entity->getEmail());
        $stm->bindValue(':pass', $entity->getPass());
        $stm->execute();

        $id = $stm->fetch(\PDO::FETCH_ASSOC);
        $entity->setId($id['id']);
    }

    public function fetchOneById($id)
    {
        $key = 'user_'.$id;
        $userArray = $this->cache->get($key);
        if ($userArray === false) {
            $query = 'SELECT * FROM users WHERE id = :userid';
            $stm = $this->conn->prepare($query);
            $stm->bindValue(':userid', $id);
            $stm->execute();

            $userArray = $stm->fetch(\PDO::FETCH_ASSOC);
            $this->cache->set($key, $userArray, 60);
        }

        $entity = new Entity;
        $entity->setId($userArray['id'])
               ->setName($userArray['name'])
               ->setEmail($userArray['email'])
               ->setPass($userArray['pass']);

        return $entity;
    }
}