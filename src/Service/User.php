<?php

namespace Ls\Api\Service;

use Ls\Api\Validation\CustomValidation;
use Ls\Api\Validation\Exception\ValidationException;

class User
{
  public function __construct()
  {
  }

  public function create(mixed $data): array|object
  {
    $validator = new CustomValidation($data);
    if ($validator->validate_create()) {
      return $data;
    }

    throw new ValidationException("Validation failed, wrong input data");
  }

  public function get(string $user_id): array|object
  {
    $validation = new CustomValidation($user_id);
    if ($validation->validate_uuid()) {
      return ["data" => "passed uuid validation"];
    }
    throw new ValidationException("Validation failed, uuid not valid");
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
    $validation = new CustomValidation($user_data);
    if ($validation->validate_update()) {
      return ["data" => "passed update validation"];
    }
    throw new ValidationException("Validation failed, wrong input data");
  }

  public function remove(string $user_id): array|object
  {
    $validation = new CustomValidation($user_id);
    if ($validation->validate_uuid()) {
      return ["data" => "passed uuid validation"];
    }
    throw new ValidationException("Validation failed, uuid not valid");
  }
}