<?php

require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/ControllerBase.php';

class TimelineController extends ControllerBase
{
  public function __construct()
  {
    parent::__construct();
  }

  public function GETAction(): void
  {
    $stmt = $this->database->getClient()->prepare(
      'SELECT `uuid`, `author`, `content`, `parent` FROM `posts`'
    );
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $posts = array_map(function ($item) {
      $post = new Post();
      return $post->fromArray($item);
    }, $result);
    $this->response->setBody($posts)->sendJSON();
  }
}
