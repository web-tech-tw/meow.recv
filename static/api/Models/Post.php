<?php

require_once __DIR__ . '/ModelBase.php';
require_once __DIR__ . '/User.php';

class Post extends ModelBase implements ModelInterface
{
  public string $uuid;
  public string|User $author;
  public string $content;
  public int $created_time;
  public ?int $modified_time;
  public ?string $parent;
  public ?array $children;

  public function checkReady(): bool
  {
    return isset($this->uuid);
  }

  public function load(Database $db_instance, mixed $filter): static
  {
    assert(is_string($filter), new AssertionError("Argument #2 should be string"));
    $stmt = $db_instance->getClient()->prepare(
      'SELECT `uuid`, `author`, `created_time`, `content`, `modified_time`, `parent` FROM `posts` WHERE `uuid` = ?'
    );
    $stmt->execute([$filter]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) === 1) {
      $this->fromArray($result[0]);
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
    if ($this->author instanceof User) {
      $this->author = $this->author->identity;
    }
    $stmt = $db_instance->getClient()->prepare(
      'INSERT INTO `posts`(`uuid`, `author`, `content`, `created_time`, `parent`) VALUES (UUID(), :author, :content, UNIX_TIMESTAMP(), :parent)'
    );
    $db_instance->bindParamsSafe($stmt, $this->toArray(), ["author", "content", "parent"]);
    return $stmt->execute();
  }

  public function replace(Database $db_instance): bool
  {
    if ($this->author instanceof User) {
      $this->author = $this->author->identity;
    }
    $stmt = $db_instance->getClient()->prepare(
      'UPDATE `posts` SET `content` = :content, `modified_time` = UNIX_TIMESTAMP() WHERE `uuid` = :uuid'
    );
    $db_instance->bindParamsSafe($stmt, $this->toArray(), ["content", "uuid"]);
    return $stmt->execute();
  }

  public function destroy(Database $db_instance): bool
  {
    $stmt = $db_instance->getClient()->prepare(
      'DELETE FROM `posts` WHERE `uuid` = ?'
    );
    return $stmt->execute([$this->uuid]);
  }


  public function isAuthor(User $user): bool
  {
    if ($this->author instanceof User) {
      return $this->author->identity === $user->identity;
    } else {
      return $this->author === $user->identity;
    }
  }

  /**
   * @param Database $db_instance
   * @return Post
   */
  public function loadAuthor(Database $db_instance): static
  {
    if ($this->author instanceof User) {
      return $this;
    }
    $query = $this->author;
    $this->author = new User();
    $this->author->load($db_instance, [false, $query]);
    return $this;
  }

  /**
   * @param Database $db_instance
   * @return Post
   */
  public function loadChildren(Database $db_instance): static
  {
    if (isset($this->children)) {
      return $this;
    }
    $stmt = $db_instance->getClient()->prepare(
      'SELECT `uuid`, `author`, `created_time`, `content`, `modified_time` FROM `posts` WHERE `parent` = ?'
    );
    $stmt->execute([$this->uuid]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $this->children = array_map(function ($item) use ($db_instance) {
      $post = new Post();
      $post->fromArray($item);
      $post->loadAuthor($db_instance);
      return $post;
    }, $result);
    return $this;
  }

  /**
   * @return string
   */
  public function getContent(): string
  {
    return $this->content;
  }

  /**
   * @param string $content
   * @return Post
   */
  public function setContent(string $content): static
  {
    $this->content = $content;
    return $this;
  }

  /**
   * @param User $author
   * @return Post
   */
  public function setAuthor(User $author): static
  {
    $this->author = $author;
    return $this;
  }
}
