<?php

require_once __DIR__ . '/../Kernel/Request.php';

use JetBrains\PhpStorm\Pure;

trait CommonCORS
{
  #[Pure]
  public function getOrigin(Request $request): string
  {
    return $request->getHeader("Origin");
  }

  public function getAllowHeaders(): array
  {
    return ["Content-Type", "X-Requested-With"];
  }

  public function getAllowCredentials(): bool
  {
    return true;
  }
}
