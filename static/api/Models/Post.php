<?php

require_once __DIR__ . '/ModelBase.php';

class Post extends ModelBase implements ModelInterface
{
  public string $uuid;
  public string $author;
  public string $content;
  public int $created_time;
  public ?string $parent;

  public function checkReady(): bool
  {
    return isset($this->uuid);
  }

  public function load(Database $db_instance, mixed $filter): static
  {
    assert(is_string($filter), new Error("Argument #2 should be string"));
    $stmt = $db_instance->getClient()->prepare(
      'SELECT `uuid`, `author`, `created_time`, `content`, `parent` FROM `posts` WHERE `uuid` = ?'
    );
    $stmt->execute([$filter]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) === 1) {
      $this->fromArray($result[0]);
    } else {
      throw new Exception("The post $filter is not found.");
    }
    return $this;
  }

  public function reload(Database $db_instance): static
  {
    $this->load($db_instance, $this->uuid);
    return $this;
  }

  public function create(Database $db_instance): bool
  {
    $stmt = $db_instance->getClient()->prepare(
      'INSERT INTO `posts`(`uuid`, `author`, `content`, `created_time`, `parent`) VALUES (UUID(), :author, :content, UNIX_TIMESTAMP(), :parent)'
    );
    $db_instance->bindParams($stmt, $this->toArray());
    return $stmt->execute();
  }

  public function replace(Database $db_instance): bool
  {
    $stmt = $db_instance->getClient()->prepare(
      'UPDATE `posts` SET `content` = :content, `modified_time` = UNIX_TIMESTAMP() WHERE `uuid` = :uuid'
    );
    $db_instance->bindParams($stmt, $this->toArray());
    return $stmt->execute();
  }

  public function destroy(Database $db_instance): bool
  {
    $stmt = $db_instance->getClient()->prepare(
      'DELETE FROM `posts` WHERE `uuid` = ?'
    );
    return $stmt->execute([$this->uuid]);
  }
}
