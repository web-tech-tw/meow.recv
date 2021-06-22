<?php

require_once __DIR__ . '/../Models/Post.php';

class PostController extends ControllerBase
{
  public function __construct()
  {
    parent::__construct();
  }

  private function GETAction(): void
  {
    $post = new Post();
    $post->load($this->database, $this->request->getQuery("uuid"));
    $this->response->setBody($post)->sendJSON();
  }

  private function POSTAction(): void
  {
    $post = new Post();
    $post
      ->fromArray($this->request->read())
      ->create($this->database);
    $this->response->setStatus(204)->send();
  }

  private function DELETEAction(): void
  {
    $post = new Post();
    $post->load($this->database, $this->request->getQuery("uuid"))->destroy();
    $this->response->setStatus(204)->send();
  }
}
