<?php

namespace Ls\Api\Routes;

use Ls\Api\Routes\Exception\NotAllowedException;
use Ls\Api\Service\Pokemon;

$action = $_REQUEST["action"] ?? null;

enum PokemonAction: string
{
  case CREATE = "create";
  case GET = "get";
  case GET_ALL = "get_all";
  case UPDATE = "update";
  case REMOVE = "remove";
  case LOGIN = "login";


  function getResponse(): string
  {
    $pokemon = new Pokemon();

    $pokemon_data = json_decode(file_get_contents("php://input"));
    $pokemon_id = $_REQUEST["id"] ?? null;

    $http_method = match ($this) {
      self::CREATE, self::LOGIN => Http::POST_METHOD,
      self::GET, self::GET_ALL => Http::GET_METHOD,
      self::UPDATE => Http::PUT_METHOD,
      self::REMOVE => Http::DELETE_METHOD
    };

    $correctRequestMethod = Http::matchHttpRequestMethod($http_method);

    if ($correctRequestMethod) {
      $response = match ($this) {
        self::CREATE => $pokemon->create($pokemon_data),
        self::GET => $pokemon->get($pokemon_id),
        self::GET_ALL => $pokemon->get_all(),
        self::UPDATE => $pokemon->update($pokemon_data),
        self::REMOVE => $pokemon->remove($pokemon_id),
        self::LOGIN => $pokemon->login($pokemon_data)
      };
      return json_encode($response);
    }
    throw new NotAllowedException("Method not allowed");
  }
}


$pokemon_action = PokemonAction::tryFrom($action);
if ($pokemon_action) {
  echo $pokemon_action->getResponse();
} else {
  require "404.routes.php";
}