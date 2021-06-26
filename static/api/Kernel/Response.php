<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

class Response
{
  private int $status = 200;
  private mixed $body;

  private const COOKIES_ROOT = "/";

  public function redirect(string $value_uri, $permanent = false): static
  {
    $this->setStatus($permanent ? 301 : 302);
    header("Location: $value_uri;", true, $this->status);
    return $this;
  }

  public function redirectForUpgrade(string $value_uri): static
  {
    $this->setStatus(307);
    header("Location: $value_uri;", true, $this->status);
    return $this;
  }

  public function setStatus(int $status): static
  {
    $this->status = $status;
    return $this;
  }

  public function setHeader(string $key, string $value, bool $replace = true): static
  {
    header("$key: $value;", $replace);
    return $this;
  }

  public function setBody(mixed $body): static
  {
    $this->body = $body;
    return $this;
  }

  public function setCookie(string $key, string $value, int $expires): static
  {
    setcookie($key, $value, [
      "expires" => $expires,
      'path' => self::COOKIES_ROOT,
      'httponly' => true,
      'samesite' => 'Strict'
    ]);
    return $this;
  }

  public function sendJSON(bool $exit = false)
  {
    header('Content-Type: application/json;charset=utf-8');
    $this->send($exit, json_encode($this->body));
  }

  public function send(bool $exit = false, ?string $deliver = null)
  {
    http_response_code($this->status);
    if (is_null($deliver) && !is_string($this->body)) {
      $deliver = serialize($this->body);
    }
    echo !is_null($deliver) ? $deliver : $this->body;
    if ($exit) exit;
  }
}
