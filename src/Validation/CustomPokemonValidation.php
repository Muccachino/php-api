<?php

namespace Ls\Api\Validation;

use Respect\Validation\Validator as v;

class CustomPokemonValidation
{
  const MAX_STRING = 50;
  const MIN_STRING = 3;
  const MIN_POKE_ID = 1;
  const MAX_POKE_ID = 1100;
  const POKE_TYPES = [
    "Normal",
    "Fire",
    "Water",
    "Electric",
    "Grass",
    "Ice",
    "Fighting",
    "Poison",
    "Ground",
    "Flying",
    "Psychic",
    "Bug",
    "Rock",
    "Ghost",
    "Dragon",
    "Dark",
    "Steel",
    "Fairy"
  ];

  public function __construct(private readonly mixed $data)
  {
  }

  public function validate_create_poke(): bool
  {
    $validation = v::attribute("name", v::stringType()->length(self::MIN_STRING, self::MAX_STRING))
      ->attribute("pokedex_id", v::intVal()->between(self::MIN_POKE_ID, self::MAX_POKE_ID));
    //TODO: Validate Poke Type;
    return $validation->validate($this->data);
  }

  public function validate_poke_uuid(): bool
  {
    return v::uuid(4)->validate($this->data);
  }

  public function validate_update_poke(): bool
  {
    $validation = v::attribute("uuid", v::uuid(4))
      ->attribute("name", v::stringType()->length(self::MIN_STRING, self::MAX_STRING))
      ->attribute("pokedex_id", v::stringType()->length(self::MIN_POKE_ID, self::MAX_POKE_ID));

    return $validation->validate($this->data);
  }
}