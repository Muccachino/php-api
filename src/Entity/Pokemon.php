<?php

namespace Ls\Api\Entity;

class Pokemon
{
  private string $uuid;
  private string $name;
  private string $pokedex_id;
  private string $trainerName;
  private string $type;
  private string $caught;

  public function getUuid(): string
  {
    return $this->uuid;
  }

  public function setUuid(string $uuid): Pokemon
  {
    $this->uuid = $uuid;
    return $this;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): Pokemon
  {
    $this->name = $name;
    return $this;
  }

  public function getPokedexId(): string
  {
    return $this->pokedex_id;
  }

  public function setPokedexId(string $pokedex_id): Pokemon
  {
    $this->pokedex_id = $pokedex_id;
    return $this;
  }

  public function getTrainerName(): string
  {
    return $this->trainerName;
  }

  public function setTrainerName(string $trainerName): Pokemon
  {
    $this->trainerName = $trainerName;
    return $this;
  }

  public function getType(): string
  {
    return $this->type;
  }

  public function setType(string $type): Pokemon
  {
    $this->type = $type;
    return $this;
  }

  public function getCaught(): string
  {
    return $this->caught;
  }

  public function setCaught(string $caught): Pokemon
  {
    $this->caught = $caught;
    return $this;
  }

  public function unSerialize($pokemon): Pokemon
  {
    if (!empty($pokemon["uuid"])) {
      $this->setUuid($pokemon["uuid"]);
    }
    if (!empty($pokemon["name"])) {
      $this->setName($pokemon["name"]);
    }
    if (!empty($pokemon["pokedex_id"])) {
      $this->setPokedexId($pokemon["pokedex_id"]);
    }
    if (!empty($pokemon["trainer_name"])) {
      $this->setTrainerName($pokemon["trainer_name"]);
    }
    if (!empty($pokemon["type"])) {
      $this->setType($pokemon["type"]);
    }
    if (!empty($pokemon["caught"])) {
      $this->setCaught($pokemon["caught"]);
    }

    return $this;
  }

  public function serialize(): array
  {
    return get_object_vars($this);
  }
}