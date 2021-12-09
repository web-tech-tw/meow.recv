<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

require_once __DIR__ . '/../../Controllers/ControllerInterface.php';

interface AllowCORS extends ControllerInterface
{
  public function getAllowOrigin(): string;

  public function getAllowMethods(): array;

  public function getAllowHeaders(): array;

  public function getAllowCredentials(): bool;
}
