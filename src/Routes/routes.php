<?php

namespace Ls\Api\Routes;

use Ls\Api\Validation\Exception\ValidationException;
use function Ls\Api\Helpers\response;

$resource = $_REQUEST["resource"] ?? null;

try {
  return match ($resource) {
    "user" => require "user.routes.php",
    default => require "404.routes.php"
  };
} catch (ValidationException $e) {
  response([
    "errors" => [
      "message" => $e->getMessage(),
      "code" => $e->getCode()
    ]
  ]);
}

