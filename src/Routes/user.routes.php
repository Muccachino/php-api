<?php

namespace Ls\Routes;

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
    $user_id = 1;
    $user_data = ["name" => "test"];
    //TODO: GET USER DATA FROM https BODY
    $user = new User("Lucas", "Staszewski", 33);

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
