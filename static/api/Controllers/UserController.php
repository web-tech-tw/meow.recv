<?php

require_once __DIR__ . '/ControllerBase.php';
require_once __DIR__ . '/CommonCORS.php';
require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/../Middlewares/Authentication.php';
require_once __DIR__ . '/../Middlewares/CORS/CORS.php';
require_once __DIR__ . '/../Middlewares/CORS/AllowCORS.php';

class UserController extends ControllerBase implements AllowCORS
{
  use CommonCORS;

  public ?User $user;

  public function __construct()
  {
    parent::__construct();;
    $this->insertMiddleware(false, "CORS");
  }

  public function getAllowOrigin(): string
  {
    return $this->config->get("CORS_HOST", false) ?? $this->getOrigin($this->request);
  }

  public function getAllowMethods(): array
  {
    return [CORS::METHOD_GET, CORS::METHOD_POST, CORS::METHOD_PATCH, CORS::METHOD_DELETE];
  }

  private static function generateRandomSeed(): string
  {
    try {
      return random_bytes(32) . time();
    } catch (Exception $exception) {
      error_log($exception->getMessage());
      return rand(0, 2147480000) . time();
    }
  }

  public function GETAction(): void
  {
    if (!Authentication::load($this)) {
      $this->response->setStatus(401)->send(true);
    }
    $this->response->setBody($this->user)->sendJSON();
  }

  public function POSTAction(): void
  {
    $data = $this->request->read();
    $this->request->assertKeysInData($this->response, "display_name", $data);
    $display_name = $data["display_name"];
    $this->request->validData($this->response, fn($item) => !empty($item), $display_name);
    $identity = hash("sha256", self::generateRandomSeed());
    $access_token = hash("sha256", self::generateRandomSeed());
    Authentication::issue($this, $access_token);
    $this->user = new User();
    $result = $this->user
      ->setIdentity($identity)
      ->setAccessToken($access_token)
      ->setDisplayName($display_name)
      ->setIpAddr($this->request->getRemoteIp())
      ->setDevice($this->request->getUserAgent())
      ->create($this->database);
    $this->response->setStatus($result ? 204 : 500)->send();
  }

  public function PATCHAction(): void
  {
    $display_name = $this->request->getQuery("display_name");
    $this->request->validData($this->response, fn($item) => !empty($item), $display_name);
    if (!Authentication::load($this)) {
      $this->response->setStatus(401)->send(true);
    }
    $result = $this->user->setDisplayName($display_name)->replace($this->database);
    $this->response->setStatus($result ? 204 : 500)->send();
  }

  public function DELETEAction(): void
  {
    if (!Authentication::load($this)) {
      $this->response->setStatus(401)->send(true);
    }
    Authentication::revoke($this);
    $this->response->setStatus(204)->send();
  }
}
