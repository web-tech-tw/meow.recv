<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

require_once __DIR__ . '/../Kernel/Config.php';
require_once __DIR__ . '/../Kernel/Database.php';
require_once __DIR__ . '/../Kernel/Request.php';
require_once __DIR__ . '/../Kernel/Response.php';
require_once __DIR__ . '/ControllerInterface.php';
require_once __DIR__ . '/../Middlewares/Authentication.php';

class ControllerBase implements ControllerInterface
{
  public Request $request;
  public Response $response;
  public Config $config;
  public Database $database;
  public array $middlewares_before;
  public array $middlewares_after;

  public function __construct()
  {
    $this->request = new Request();
    $this->response = new Response();
    $this->config = new Config();
    $this->database = new Database($this->config);
    $this->middlewares_before = [
      "Authentication"
    ];
    $this->middlewares_after = [];
  }

  public function trigger(): void
  {
    $method = $this->request->getMethod();
    if (method_exists($this, "{$method}Action")) {
      foreach ($this->middlewares_before as $class) {
        call_user_func("$class::trigger", $this);
      }
      $this->{"{$method}Action"}();
      foreach ($this->middlewares_after as $class) {
        call_user_func("$class::trigger", $this);
      }
    } else {
      http_response_code(405);
      echo "Method Not Allowed";
    }
  }
}
