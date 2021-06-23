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
    $timestamp = time();
    try {
      $seed = random_bytes(32) . $timestamp;
    } catch (Exception $exception) {
      $seed = rand(0, 4096) . $timestamp;
      error_log($exception->getMessage());
    }
    $identity = hash("sha256", $seed);
    $access_token = hash("sha256", $seed . $timestamp . $seed);
    $controller->response->setCookie(
      self::ACCESS_COOKIE,
      $access_token,
      $timestamp + self::ACCESS_COOKIE_EXPIRES
    );
    return $controller->user
      ->setIdentity($identity)
      ->setAccessToken($access_token)
      ->setDisplayName($controller->request->getIpAddr())
      ->setIpAddr($controller->request->getIpAddr())
      ->setDevice($controller->request->getUserAgent())
      ->create($controller->database);
  }

  public static function trigger(ControllerInterface $controller): void
  {
    $identity = $controller->request->getCookie(self::ACCESS_COOKIE);
    $controller->user = new User();
    $controller->user->load($controller->database, [true, $identity]);
    if (!$controller->user->checkReady()) {
      self::createNewUser($controller);
    }
  }
}
