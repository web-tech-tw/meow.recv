<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

interface ModelInterface
{
  public function load(Database $db_instance, string $uuid): static;

  public function reload(Database $db_instance): static;

  public function create(Database $db_instance): bool;

  public function modify(Database $db_instance): bool;

  public function destroy(Database $db_instance): bool;

  public function fromArray(array $array): static;

  public function toArray(): array;
}
