<?php

namespace Ls\Api\Service;
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


    return [
      "firstname" => $this->firstname,
      "lastname" => $this->lastname,
      "age" => $this->age
    ];
  }

  public function get(int $user_id): array|object
  {

    return [
      "getUserId" => $user_id
    ];
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

  public function remove(int $user_id): array|object
  {
    return [
      "delete" => "Check"
    ];
  }
}