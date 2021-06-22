<?php

require_once __DIR__ . '/ModelBase.php';

class Post extends ModelBase implements ModelInterface
{
  private string $uuid;
  private string $author;
  private string $content;
  private int $created_time;
  private ?string $parent;

  public function load(Database $db_instance, mixed $filter): static
  {
    assert(is_string($filter), new Error("Argument #2 should be string"));
    $stmt = $db_instance->getClient()->prepare(
      'SELECT `uuid`, `author`, `created_time`, `content`, `parent` FROM `posts` WHERE `uuid` = ?'
    );
    $stmt->execute([$filter]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $this->fromArray($result);
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
