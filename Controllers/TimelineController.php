<?php

require_once __DIR__ . '/ControllerBase.php';
require_once __DIR__ . '/CommonCORS.php';
require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/../Middlewares/Authentication.php';
require_once __DIR__ . '/../Middlewares/CORS/CORS.php';
require_once __DIR__ . '/../Middlewares/CORS/AllowCORS.php';

class TimelineController extends ControllerBase implements AllowCORS
{
  use CommonCORS;

  public ?User $user;

  public function __construct()
  {
    parent::__construct();;
    $this->insertMiddleware(false, "CORS");
    $this->insertMiddleware(false, "Authentication");
  }

  public function getAllowOrigin(): string
  {
    return $this->config->get("CORS_HOST", false) ?? $this->getOrigin($this->request);
  }

  public function getAllowMethods(): array
  {
    return [CORS::METHOD_GET];
  }

  public function GETAction(): void
  {
    $stmt = $this->database->getClient()->prepare(
      'SELECT `uuid`, `author`, `created_time`, `content`, `modified_time`, `link` FROM `posts` WHERE `parent` IS NULL ORDER BY `created_time` DESC'
    );
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $posts = array_map(function ($item) {
      $post = new Post();
      $post->fromArray($item);
      $post
        ->loadLink($this->database)
        ->loadAuthor($this->database)
        ->loadChildren($this->database);
      if ($post->getLink()->checkReady()) {
        $post->getLink()->loadAuthor($this->database);
      }
      return $post;
    }, $result);
    $this->response->setBody($posts)->sendJSON();
  }
}