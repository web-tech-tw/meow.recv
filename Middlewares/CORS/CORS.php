<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../MiddlewareInterface.php';
require_once __DIR__ . '/../../Controllers/ControllerInterface.php';

class CORS implements MiddlewareInterface
{
  public const METHOD_GET = "GET";
  public const METHOD_POST = "POST";
  public const METHOD_PUT = "PUT";
  public const METHOD_PATCH = "PATCH";
  public const METHOD_DELETE = "DELETE";
  public const METHOD_OPTIONS = "OPTIONS";

  public static function rewrite(AllowCORS $controller)
  {
    $controller->getResponse()
      ->setHeader("Access-Control-Allow-Origin", $controller->getAllowOrigin())
      ->setHeader("Access-Control-Allow-Methods", implode(", ", $controller->getAllowMethods()))
      ->setHeader("Access-Control-Allow-Headers", implode(", ", $controller->getAllowHeaders()));
    if ($controller->getAllowCredentials()) {
      $controller->getResponse()
        ->setHeader("Access-Control-Allow-Credentials", "true");
    }
  }

  public static function trigger(ControllerInterface $controller): void
  {
    assert($controller instanceof AllowCORS, new AssertionError("The controller is not allowed CORS."));
    if (!$controller->getConfig()->get("CORS", false)) return;
    self::rewrite($controller);
    if ($controller->getRequest()->getMethod() === self::METHOD_OPTIONS) {
      $controller->getResponse()->setStatus(204)->send(true);
    }
  }
}
