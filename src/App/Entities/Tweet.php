<?php

namespace App\Entities;

class Tweet
{
    protected $id;
    protected $content;
    protected $author;
    protected $created;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }


    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }


    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }


    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }
}