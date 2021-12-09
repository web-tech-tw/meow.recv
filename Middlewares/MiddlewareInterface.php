<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

interface MiddlewareInterface
{
  public static function trigger(ControllerInterface $controller): void;
}
