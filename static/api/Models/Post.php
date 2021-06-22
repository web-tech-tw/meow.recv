<?php

class Post extends ModelBase implements JsonSerializable
{
  private string $uuid;
  private string $author;
  private string $content;
  private ?string $parent;
  private int $created_time;

  public function load(Database $db_instance, string $uuid): static
  {
    $stmt = $db_instance->getClient()->prepare(
      'SELECT `uuid`, `author`, `content`, `parent` FROM `posts` WHERE `uuid` = ?'
    );
    $stmt->execute($uuid);
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
      'INSERT INTO `posts`(`uuid`, `author`, `content`, `parent`) VALUES (UUID(), :author, :content, :parent)'
    );
    $db_instance->bindParams($stmt, $this->toArray());
    return $stmt->execute();
  }

  public function modify(Database $db_instance): bool
  {
    $stmt = $db_instance->getClient()->prepare(
      'UPDATE `posts` SET `content` = :content WHERE `uuid` = :uuid'
    );
    $db_instance->bindParams($stmt, $this->toArray());
    return $stmt->execute();
  }

  public function destroy(Database $db_instance): bool
  {
    $stmt = $db_instance->getClient()->prepare(
      'DELETE FROM `posts` WHERE uuid = ?'
    );
    return $stmt->execute($this->uuid);
  }
}
