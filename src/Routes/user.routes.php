<?php

namespace Ls\Api\Routes;

use Ls\Api\Service\User;


$action = $_REQUEST["action"] ?? null;

enum UserAction: string
{
  case CREATE = "create";
  case GET = "get";
  case GET_ALL = "get_all";
  case UPDATE = "update";
  case REMOVE = "remove";


  function getResponse(): string
  {
    $user = new User();

    $user_data = json_decode(file_get_contents("php://input"));
    $user_id = $_REQUEST["id"] ?? null;

    $response = match ($this) {
      self::CREATE => $user->create($user_data),
      self::GET => $user->get($user_id),
      self::GET_ALL => $user->getAll(),
      self::UPDATE => $user->update($user_data),
      self::REMOVE => $user->remove($user_id)
    };
    return json_encode($response);
  }
}

$user_action = UserAction::tryFrom($action);
if ($user_action) {
  echo $user_action->getResponse();
} else {
  require "404.routes.php";
}
