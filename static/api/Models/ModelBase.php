<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

use JetBrains\PhpStorm\Pure;

class ModelBase implements ModelInterface
{
  public function load(Database $db_instance, string $uuid): static
  {
    return $this;
  }

  public function reload(Database $db_instance): static
  {
    return $this;
  }

  public function create(Database $db_instance): bool
  {
    return false;
  }

  public function modify(Database $db_instance): bool
  {
    return false;
  }

  public function destroy(Database $db_instance): bool
  {
    return false;
  }

  public function fromArray(array $array): static
  {
    foreach ($array as $key => $value) {
      $this->{$key} = $value;
    }
    return $this;
  }

  public function toArray(): array
  {
    return (array)$this;
  }

  #[Pure]
  public function jsonSerialize(): array
  {
    return $this->toArray();
  }
}
