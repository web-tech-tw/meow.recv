<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/MiddlewareInterface.php';
require_once __DIR__ . '/../Controllers/ControllerInterface.php';

class Authentication implements MiddlewareInterface
{
  private const ACCESS_COOKIE = "access_cookie";
  private const ACCESS_COOKIE_EXPIRES = 86400;

  public static function createNewUser(ControllerInterface $controller): bool
  {
    try {
      $seed = random_bytes(32) . time();
    } catch (Exception $exception) {
      $seed = rand(0, 4096) . time();
      error_log($exception->getMessage());
    }
    $identity = hash("sha256", $seed);
    $controller->response->setCookie(
      self::ACCESS_COOKIE,
      $identity,
      self::ACCESS_COOKIE_EXPIRES
    );
    return $controller->user
      ->setIdentity($identity)
      ->setDisplayName($controller->request->getIpAddr())
      ->setIpAddr($controller->request->getIpAddr())
      ->setDevice($controller->request->getUserAgent())
      ->create($controller->database);
  }

  public static function trigger(ControllerInterface $controller): void
  {
    $identity = $controller->request->getCookie(self::ACCESS_COOKIE);
    $controller->user = new User();
    $controller->user->load($controller->database, $identity);
    if (!$controller->user->checkReady()) {
      self::createNewUser($controller);
    }
  }
}
