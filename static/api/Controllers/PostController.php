<?php

use JetBrains\PhpStorm\Pure;

require_once __DIR__ . '/ControllerBase.php';
require_once __DIR__ . '/CommonCORS.php';
require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/../Middlewares/Authentication.php';
require_once __DIR__ . '/../Middlewares/CORS/CORS.php';
require_once __DIR__ . '/../Middlewares/CORS/AllowCORS.php';

class PostController extends ControllerBase implements AllowCORS
{
  use CommonCORS;

  public ?User $user;

  public function __construct()
  {
    parent::__construct();
    $this->insertMiddleware(false, "CORS");
    $this->insertMiddleware(false, "Authentication");
  }

  #[Pure]
  public function getAllowOrigin(): string
  {
    return $this->getOrigin($this->request);
  }

  public function getAllowMethods(): array
  {
    return [CORS::METHOD_GET, CORS::METHOD_POST, CORS::METHOD_DELETE];
  }

  public function GETAction(): void
  {
    $post = new Post();
    $uuid = $this->request->getQuery("uuid");
    if (!empty($uuid)) {
      $post->load($this->database, $uuid);
      if ($post->checkReady()) {
        $post->loadAuthor($this->database)->loadChildren($this->database);
        $this->response->setBody($post)->sendJSON();
      } else {
        $this->response->setStatus(404)->setBody("Not Found")->send();
      }
    } else {
      $this->response->setStatus(400)->setBody("Bad Request")->send();
    }
  }

  public function POSTAction(): void
  {
    $post = new Post();
    $post
      ->fromArray($this->request->read())
      ->setAuthor($this?->user);
    if (!empty($post->getContent())) {
      $post->create($this->database);
      $this->response->setStatus(204)->send();
    } else {
      $this->response->setStatus(400)->setBody("Bad Request")->send();
    }
  }

  public function DELETEAction(): void
  {
    $post = new Post();
    $result = $post
      ->load($this->database, $this->request->getQuery("uuid"))
      ->destroy($this->database);
    $this->response->setStatus($result ? 204 : 500)->send();
  }
}
