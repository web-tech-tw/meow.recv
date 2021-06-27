<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

interface ControllerInterface
{
  public function getRequest(): Request;

  public function getResponse(): Response;

  public function getConfig(): Config;

  public function getDatabase(): Database;

  public function trigger(): void;
}
