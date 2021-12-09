<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

require_once __DIR__ . '/../Kernel/Config.php';
require_once __DIR__ . '/../Kernel/Database.php';
require_once __DIR__ . '/../Kernel/Request.php';
require_once __DIR__ . '/../Kernel/Response.php';
require_once __DIR__ . '/ControllerInterface.php';

class ControllerBase implements ControllerInterface
{
  protected Request $request;
  protected Response $response;
  protected Config $config;
  protected Database $database;
  private array $middlewares_before = [];
  private array $middlewares_after = [];

  public function __construct()
  {
    $this->request = new Request();
    $this->response = new Response();
    $this->config = new Config();
    $this->database = new Database($this->config);
  }

  /**
   * @return Request
   */
  public function getRequest(): Request
  {
    return $this->request;
  }

  /**
   * @return Response
   */
  public function getResponse(): Response
  {
    return $this->response;
  }

  /**
   * @return Config
   */
  public function getConfig(): Config
  {
    return $this->config;
  }

  /**
   * @return Database
   */
  public function getDatabase(): Database
  {
    return $this->database;
  }

  public function insertMiddleware(bool $type, string $task): static
  {
    $middlewares = [&$this->middlewares_before, &$this->middlewares_after];
    array_push($middlewares[(int)($type)], $task);
    return $this;
  }

  public function trigger(): void
  {
    foreach ($this->middlewares_before as $class) {
      call_user_func("$class::trigger", $this);
    }
    $method = $this->request->getMethod();
    if (method_exists($this, "{$method}Action")) {
      $this->{"{$method}Action"}();
    } else {
      http_response_code(405);
      echo "Method Not Allowed";
    }
    foreach ($this->middlewares_after as $class) {
      call_user_func("$class::trigger", $this);
    }
  }
}
