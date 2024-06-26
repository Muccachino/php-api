<?php

namespace Ls\Api\Routes;

use Ls\Api\Routes\Exception\NotAllowedException;
use Ls\Api\Service\Exception\CanNotLoginUserException;
use Ls\Api\Service\Exception\EmailExistsException;
use Ls\Api\Validation\Exception\ValidationException;
use PH7\JustHttp\StatusCode;
use function Ls\Api\Helpers\response;

$resource = $_REQUEST["resource"] ?? null;

try {
  return match ($resource) {
    "user" => require "user.routes.php",
    "pokemon" => require "pokemon.routes.php",
    default => require "404.routes.php"
  };
} catch (ValidationException|NotAllowedException $e) {
  \PH7\PhpHttpResponseHeader\Http::setHeadersByCode(StatusCode::BAD_REQUEST);
  response([
    "errors" => [
      "message" => $e->getMessage(),
      "code" => $e->getCode()
    ]
  ]);
} catch (EmailExistsException|CanNotLoginUserException $e) {
  response([
    "errors" => [
      "message" => $e->getMessage()
    ]
  ]);
}

