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
  public Request $request;
  public Response $response;
  public Config $config;
  public Database $database;

  public function __construct()
  {
    $this->request = new Request();
    $this->response = new Response();
    $this->config = new Config();
    $this->database = new Database($this->config);
  }

  public function trigger(): void
  {
    $method = $this->request->getMethod();
    if (method_exists($this, "{$method}Action")) {
      $this->{"{$method}Action"}();
    } else {
      http_response_code(405);
      echo "Method Not Allowed";
    }
  }
}
