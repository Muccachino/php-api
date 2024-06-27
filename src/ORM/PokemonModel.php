<?php

namespace Ls\Api\ORM;

use Exception;
use Ls\Api\Entity\Pokemon as PokemonEntity;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class PokemonModel
{
  const TABLE_NAME = 'pokemon';


  public static function create(PokemonEntity $data): false|string
  {
    $poke_bean = R::dispense(self::TABLE_NAME);
    $poke_bean->uuid = $data->getUuid();
    $poke_bean->name = $data->getName();
    $poke_bean->pokedex_id = $data->getPokedexId();
    $poke_bean->trainerName = $data->getTrainerName();
    $poke_bean->type = $data->getType();
    $poke_bean->caught = $data->getCaught();

    try {
      $poke_bean_id = R::store($poke_bean);
    } catch (SQL $e) {
      return false;
    } finally {
      R::close();
    }

    $poke_bean = R::load(self::TABLE_NAME, $poke_bean_id);
    return $poke_bean->uuid;
  }

  public static function get_all(): false|array
  {
    $poke_beans = R::findAll(self::TABLE_NAME);
    $poke_exists = $poke_beans && count($poke_beans);

    if (!$poke_exists) {
      return false;
    }

    return array_map(function ($poke): array {
      $entity = new PokemonEntity();
      $entity->unSerialize($poke->export());
      return [
        "uuid" => $entity->getUuid(),
        "name" => $entity->getName(),
        "type" => $entity->getType(),
        "pokedex_id" => $entity->getPokedexId(),
        "trainerName" => $entity->getTrainerName(),
        "caught" => $entity->getCaught(),
      ];
    }, $poke_beans);
  }

  public static function deleteByUuid(string $uuid): bool
  {
    $poke = R::findOne(self::TABLE_NAME, 'uuid = :uuid', ["uuid" => $uuid]);
    try {
      R::trash($poke);
      return true;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getByUuid(string $uuid): ?PokemonEntity
  {
    $poke = R::findOne(self::TABLE_NAME, 'uuid = :uuid', ["uuid" => $uuid]);
    $poke_bean_export = $poke->export();
    $poke_entity = new PokemonEntity();
    return $poke_entity->unserialize($poke_bean_export);
  }

  public static function updateByUuid(string $uuid, PokemonEntity $pokemon): bool|object
  {
    $poke_bean = R::findOne(self::TABLE_NAME, 'uuid = :uuid', ["uuid" => $uuid]);

    if (!$poke_bean) {
      if ($pokemon->getTrainerName()) {
        $poke_bean->trainerName = $pokemon->getTrainerName();
      }
      try {
        $poke_bean_id = R::trash($poke_bean);
      } catch (Exception $e) {
        return false;
      } finally {
        R::close();
      }

      $updated_poke_bean = R::findOne(self::TABLE_NAME, 'uuid = :uuid', ["uuid" => $poke_bean_id]);
      unset($updated_poke_bean->id);
      return $updated_poke_bean;
    }
    return false;
  }

  public static function trainerExists(string $trainerName): bool
  {
    $poke_bean = R::findOne(self::TABLE_NAME, 'trainerName = :trainerName', ["trainerName" => $trainerName]);
    return $poke_bean !== null;
  }

}