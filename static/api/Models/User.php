<?php


class User extends ModelBase implements ModelInterface
{
  public string $identity;
  public string $display_name;
  public string $device;
  public string $ip_addr;
  public string $created_time;

  public function checkReady(): bool
  {
    return isset($this->identity);
  }

  public function load(Database $db_instance, mixed $filter): static
  {
    assert(is_string($filter), new Error("Argument #2 should be string"));
    $stmt = $db_instance->getClient()->prepare(
      'SELECT `identity`, `display_name`, `device`, `ip_addr`, `created_time` FROM `users` WHERE `identity` = ?'
    );
    $stmt->execute([$filter]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) === 1) {
      $this->fromArray($result[0]);
    } else {
      throw new Exception("The user $filter is not found.");
    }
    return $this;
  }

  public function reload(Database $db_instance): static
  {
    $this->load($db_instance, $this->identity);
    return $this;
  }

  public function create(Database $db_instance): bool
  {
    $stmt = $db_instance->getClient()->prepare(
      'INSERT INTO `users`(`identity`, `display_name`, `device`, `ip_addr`, `created_time`) VALUES (:identity, :display_name, :device, :ip_addr, UNIX_TIMESTAMP())'
    );
    $db_instance->bindParams($stmt, $this->toArray());
    return $stmt->execute();
  }

  public function replace(Database $db_instance): bool
  {
    $stmt = $db_instance->getClient()->prepare(
      'UPDATE `users` SET `display_name` = :display_name WHERE `identity` = :identity'
    );
    $db_instance->bindParams($stmt, $this->toArray());
    return $stmt->execute();
  }

  public function destroy(Database $db_instance): bool
  {
    $stmt = $db_instance->getClient()->prepare(
      'DELETE FROM `users` WHERE `identity` = ?'
    );
    return $stmt->execute([$this->identity]);
  }

  /**
   * @return string
   */
  public function getIdentity(): string
  {
    return $this->identity;
  }

  /**
   * @param string $identity
   * @return User
   */
  public function setIdentity(string $identity): static
  {
    $this->identity = $identity;
    return $this;
  }

  /**
   * @param string $display_name
   * @return User
   */
  public function setDisplayName(string $display_name): static
  {
    $this->display_name = $display_name;
    return $this;
  }

  /**
   * @param string $device
   * @return User
   */
  public function setDevice(string $device): static
  {
    $this->device = $device;
    return $this;
  }

  /**
   * @param string $ip_addr
   * @return User
   */
  public function setIpAddr(string $ip_addr): static
  {
    $this->ip_addr = $ip_addr;
    return $this;
  }
}
