<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

class Request
{
  private string $request_uri;
  private string $method;
  private string $body;

  public function __construct()
  {
    $this->request_uri = $_SERVER["REQUEST_URI"];
    $this->method = $_SERVER["REQUEST_METHOD"];
    $this->body = file_get_contents("php://input");
  }

  /**
   * @return string
   */
  public function getRequestUri(): string
  {
    return $this->request_uri;
  }

  /**
   * @return string
   */
  public function getMethod(): string
  {
    return $this->method;
  }

  /**
   * @return string
   */
  public function getBody(): string
  {
    return $this->body;
  }

  public function getQuery(string $key): string
  {
    return $_GET[$key] ?? "";
  }

  public function readForm(): array
  {
    parse_str($this->getBody(), $result);
    return $result;
  }

  public function readJSON(): array
  {
    return json_decode($this->body, true);
  }

  public function read(): array
  {
    $content_type = $_SERVER["CONTENT_TYPE"] ?? "application/x-www-form-urlencoded";
    $content_type_array = explode(";", $content_type);
    return match ($content_type_array[0]) {
      "application/x-www-form-urlencoded" => $this->readForm(),
      "application/json" => $this->readJSON(),
      default => []
    };
  }
}
