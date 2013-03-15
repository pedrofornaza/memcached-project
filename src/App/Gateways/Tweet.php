<?php

namespace App\Gateways;

use App\Entities\Tweet as Entity;

class Tweet
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
        $query = 'INSERT INTO tweets (content, created, author) VALUES (:content, :created, :author) RETURNING id';
        $stm = $this->conn->prepare($query);

        $stm->bindValue(':content', $entity->getContent());
        $stm->bindValue(':created', $entity->getCreated());
        $stm->bindValue(':author', $entity->getAuthor());
        $stm->execute();

        $id = $stm->fetch(\PDO::FETCH_ASSOC);
        $entity->setId($id['id']);
    }

    public function fetchAll()
    {
        $key = 'tweets';
        $tweetsResult = $this->cache->get($key);
        if ($tweetsResult === false) {
            $query = 'SELECT * FROM tweets ORDER BY created ASC';
            $stm = $this->conn->prepare($query);
            $stm->execute();

            $tweetsResult = $stm->fetchAll(\PDO::FETCH_ASSOC);
            $this->cache->set($key, $tweetsResult, 60);
        }

        $userGateway = new User($this->conn, $this->cache);

        $collection = array();
        foreach ($tweetsResult as $tweetArray) {
            $author = $userGateway->fetchOneById($tweetArray['author']);

            $entity = new Entity;
            $entity->setId($tweetArray['id'])
                   ->setContent($tweetArray['content'])
                   ->setCreated($tweetArray['created'])
                   ->setAuthor($author);

            $collection[] = clone $entity;
        }

        return $collection;
    }
}