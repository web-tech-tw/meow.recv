<?php

require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/ControllerBase.php';
require_once __DIR__ . '/../Middlewares/Authentication.php';

class TimelineController extends ControllerBase
{
  public ?User $user;

  public function __construct()
  {
    parent::__construct();
    $this->insertMiddleware(false, "Authentication");
  }

  public function GETAction(): void
  {
    $stmt = $this->database->getClient()->prepare(
      'SELECT `uuid`, `author`, `created_time`, `content`, `modified_time` FROM `posts` WHERE `parent` IS NULL'
    );
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $posts = array_map(function ($item) {
      $post = new Post();
      $post->fromArray($item);
      $post->loadAuthor($this->database)->loadChildren($this->database);
      return $post;
    }, $result);
    $this->response->setBody($posts)->sendJSON();
  }
}
