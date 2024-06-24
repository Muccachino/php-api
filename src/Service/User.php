<?php

namespace Ls\Api\Service;

use Ls\Api\Validation\CustomValidation;

class User
{
  private string $firstname;
  private string $lastname;
  private int $age;

  public function __construct(string $firstname, string $lastname, int $age)
  {
    $this->firstname = $firstname;
    $this->lastname = $lastname;
    $this->age = $age;
  }

  public function create(mixed $data): array|object
  {
    $validator = new CustomValidation($data);
    if ($validator->validate_create()) {
      return $data;
    }

    return [];
  }

  public function get(string $user_id): array|object
  {
    $validation = new CustomValidation($user_id);
    if ($validation->validate_uuid()) {
      return ["data" => "passed uuid validation"];
    } else {
      return ["data" => "not passed uuid"];
    }
  }

  public function getAll(): array|object
  {
    return [
      [
        "user1" => "Check"
      ],
      [
        "user2" => "Check"
      ]
    ];
  }

  public function update(mixed $user_data): array|object
  {
    return [
      "update" => "Check"
    ];
  }

  public function remove(string $user_id): array|object
  {
    return [
      "delete" => "Check"
    ];
  }
}