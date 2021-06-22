<?php

require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/ControllerBase.php';

class PostController extends ControllerBase
{
  public User $user;

  public function __construct()
  {
    parent::__construct();
  }

  public function GETAction(): void
  {
    $post = new Post();
    $uuid = $this->request->getQuery("uuid");
    if (!empty($uuid)) {
      $post->load($this->database, $uuid)->loadAuthor($this->database);
      $this->response->setBody($post)->sendJSON();
    } else {
      $this->response
        ->setStatus(400)
        ->setBody("Bad Request")
        ->send();
    }
  }

  public function POSTAction(): void
  {
    $post = new Post();
    $post
      ->fromArray($this->request->read())
      ->setAuthor($this?->user)
      ->create($this->database);
    $this->response->setStatus(204)->send();
  }

  public function DELETEAction(): void
  {
    $post = new Post();
    $post->load($this->database, $this->request->getQuery("uuid"))->destroy();
    $this->response->setStatus(204)->send();
  }
}
