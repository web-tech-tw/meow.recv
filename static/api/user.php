<?php
require_once __DIR__ . '/Controllers/UserController.php';
$instance = new UserController();
$instance->trigger();
