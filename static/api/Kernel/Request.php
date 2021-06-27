<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

class Request
{
  private string $request_uri;
  private string $ip_addr;
  private string $user_agent;
  private string $method;
  private string $body;

  public function __construct()
  {
    $this->request_uri = $_SERVER["REQUEST_URI"];
    $this->ip_addr = $_SERVER["REMOTE_ADDR"];
    $this->user_agent = $_SERVER["HTTP_USER_AGENT"];
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
  public function getRemoteIp(): string
  {
    return $this->ip_addr;
  }

  /**
   * @return string
   */
  public function getUserAgent(): string
  {
    return $this->user_agent;
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

  public function getCookie(string $key): string
  {
    return $_COOKIE[$key] ?? "";
  }

  public function getHeader(string $key): string
  {
    $key = strtoupper($key);
    return $_SERVER["HTTP_$key"] ?? "";
  }

  public static function assertKeysInData(Response $response, array|string $key, array $data): void
  {
    $key = is_array($key) ? $key : [$key];
    $result = array_filter($key, fn($name) => !array_key_exists($name, $data));
    if (empty($result)) return;
    $response->setStatus(400)->setBody([
      "status" => 400,
      "message" => "Bad Request",
      "reason" => "Missing the argument(s)",
      "missing" => $result
    ])->sendJSON(true);
  }

  public static function validData(Response $response, callable $validator, mixed $data): void
  {
    if (!($reason = call_user_func($validator, $data))) {
      $response->setStatus(400)->setBody([
        "status" => 400,
        "message" => "Bad Request",
        "reason" => $reason,
      ])->sendJSON(true);
    }
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
