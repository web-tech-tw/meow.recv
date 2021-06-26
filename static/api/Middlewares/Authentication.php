<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/MiddlewareInterface.php';
require_once __DIR__ . '/../Controllers/ControllerInterface.php';

class Authentication implements MiddlewareInterface
{
  private const ACCESS_COOKIE = "access_cookie";
  private const ACCESS_COOKIE_EXPIRES = 86400;

  public static function issue(ControllerInterface $controller, string $access_token)
  {
    $expires = time() + Authentication::ACCESS_COOKIE_EXPIRES;
    $controller->getResponse()->setCookie(Authentication::ACCESS_COOKIE, $access_token, $expires);
  }

  public static function load(ControllerInterface $controller): bool
  {
    $identity = $controller->getRequest()->getCookie(self::ACCESS_COOKIE);
    if (empty($identity)) return false;
    $controller->user = new User();
    $controller->user->load($controller->getDatabase(), [true, $identity]);
    return $controller->user->checkReady();
  }

  public static function trigger(ControllerInterface $controller): void
  {
    if (!self::load($controller)) {
      $controller->getResponse()->setStatus(401)->send(true);
    }
  }
}
