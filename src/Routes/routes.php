<?php

namespace Ls\Api\Routes;

$resource = $_REQUEST["resource"] ?? null;

return match ($resource) {
  "user" => require "user.routes.php",
  default => require "404.routes.php"
};