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
    return [CORS::METHOD_GET, CORS::METHOD_POST, CORS::METHOD_PUT, CORS::METHOD_DELETE];
  }

  public function GETAction(): void
  {
    $uuid = $this->request->getQuery("uuid");
    $this->request->validData($this->response, fn($item) => !empty($item), $uuid);
    $post = new Post();
    $post->load($this->database, $uuid);
    if ($post->checkReady()) {
      $post
        ->loadLink($this->database)
        ->loadAuthor($this->database)
        ->loadChildren($this->database);
      if ($post->getLink()->checkReady()) {
        $post->getLink()->loadAuthor($this->database);
      }
      $this->response->setBody($post)->sendJSON();
    } else {
      $this->response->setStatus(404)->setBody("Not Found")->send();
    }
  }

  public function POSTAction(): void
  {
    $post = new Post();
    $post->fromArray($this->request->read())->setAuthor($this->user);
    $this->request->validData($this->response, fn($item) => !empty($item), $post->getContent());
    $this->request->validData($this->response, fn($item) => !$item, $post->isConflict());
    $post->create($this->database);
    $this->response->setStatus(204)->send();
  }

  public function PUTAction(): void
  {
    $data = $this->request->read();
    $this->request->assertKeysInData($this->response, ["uuid", "content"], $data);
    $uuid = $data["uuid"];
    $content = $data["content"];
    $this->request->validData($this->response, fn($item) => !empty($item), $uuid);
    $this->request->validData($this->response, fn($item) => !empty($item), $content);
    $post = new Post();
    $post->load($this->database, $uuid);
    if (!$post->isAuthor($this->user)) $this->response->setStatus(403)->send(true);
    $post->setContent($content);
    $post->replace($this->database);
    $this->response->setStatus(204)->send();
  }

  public function DELETEAction(): void
  {
    $post = new Post();
    $post->load($this->database, $this->request->getQuery("uuid"));
    if (!$post->isAuthor($this->user)) $this->response->setStatus(403)->send(true);
    $result = $post->destroy($this->database);
    $this->response->setStatus($result ? 204 : 500)->send();
  }
}
