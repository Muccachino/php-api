<?php

namespace Ls\Api\Service;

use Ls\Api\Entity\Pokemon as PokemonEntity;
use Ls\Api\ORM\PokemonModel;
use Ls\Api\Validation\CustomPokemonValidation;
use Ls\Api\Validation\Exception\ValidationException;
use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Ramsey\Uuid\Uuid;

class Pokemon
{
  public function __construct()
  {
  }

  public function create(mixed $data): array|object
  {
    $validator = new CustomPokemonValidation($data);
    if ($validator->validate_create_poke()) {
      $uuid = Uuid::uuid4()->toString();
      $poke_entity = new PokemonEntity();
      $poke_entity->setUuid($uuid);
      $poke_entity->setName($data->name);
      $poke_entity->setType($data->type);
      $poke_entity->setPokedexId($data->pokedex_id);
      $poke_entity->setTrainerName($data->trainerName);
      $poke_entity->setCaught(date("Y-m-d H:i:s"));

      $valid = $poke_uuid = PokemonModel::create($poke_entity);
      if (!$valid) {
        Http::setHeadersByCode(StatusCode::BAD_REQUEST);
        return [];
      }
      $data->uuid = $poke_uuid;
      return $data;
    }
    throw new ValidationException("Invalid Pokemon Data");
  }

  public function get(string $poke_id): array|object
  {
    $validation = new CustomPokemonValidation($poke_id);
    if ($validation->validate_poke_uuid()) {
      if ($poke_bean = PokemonModel::getByUuid($poke_id)) {
        return $poke_bean->serialize();
      }
      HTTP::setHeadersByCode(StatusCode::NOT_FOUND);
      return [];
    }
    throw new ValidationException("Validation failed, uuid not valid");
  }

  public function get_all(): array|object|false
  {
    return PokemonModel::get_all();
  }

  public function update(mixed $poke_data): array|object
  {
    $validator = new CustomPokemonValidation($poke_data);
    if ($validator->validate_update_poke()) {
      $poke_uuid = $poke_data->uuid;
      $poke_entity = new PokemonEntity();

      if (!empty($poke_data->trainerName)) {
        $poke_entity->setTrainerName($poke_data->trainerName);
      }

      $valid = $updated_poke = PokemonModel::updateByUuid($poke_uuid, $poke_entity);
      if (!$valid) {
        Http::setHeadersByCode(StatusCode::NOT_FOUND);
        return [];
      }
      return $updated_poke;
    }
    throw new ValidationException("Invalid Pokemon Data");
  }

  public function remove(string $poke_id): array|object
  {
    $validation = new CustomPokemonValidation($poke_id);
    if ($validation->validate_poke_uuid()) {
      $valid = $delete_poke = PokemonModel::deleteByUuid($poke_id);
      if (!$valid) {
        Http::setHeadersByCode(StatusCode::NOT_FOUND);
        return ["error" => "Pokemon not found"];
      }
      return ["data" => $delete_poke];
    }
    throw new ValidationException("Validation failed, uuid not valid");
  }
}