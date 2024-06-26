<?php

namespace Ls\Api\Service;

use Ls\Api\Entity\User as UserEntity;
use Ls\Api\ORM\UserModel;
use Ls\Api\Service\Exception\EmailExistsException;
use Ls\Api\Validation\CustomValidation;
use Ls\Api\Validation\Exception\ValidationException;
use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Ramsey\Uuid\Uuid;

class User
{
  public function __construct()
  {
  }

  public function create(mixed $data): array|object
  {
    $validator = new CustomValidation($data);
    if ($validator->validate_create()) {
      $uuid = Uuid::uuid4()->toString();
      $user_entity = new UserEntity();
      $user_entity->setUuid($uuid)
        ->setFirstname($data->firstname)
        ->setLastname($data->lastname)
        ->setEmail($data->email)
        ->setPhone($data->phone)
        ->setCreatedAt(date("Y-m-d H:i:s"));
      //TODO: set Password

      if (UserModel::emailExists($user_entity->getEmail())) {
        $email = $user_entity->getEmail();
        throw new EmailExistsException("Email $email already exists");
      }
      $valid = $user_uuid = UserModel::create($user_entity);
      if (!$valid) {
        Http::setHeadersByCode(StatusCode::BAD_REQUEST);
        return [];
      }
      $data->uuid = $user_uuid;
      return $data;

    }
    throw new ValidationException("Validation failed, wrong input data");

  }

  public function get(string $user_id): array|object
  {
    $validation = new CustomValidation($user_id);
    if ($validation->validate_uuid()) {
      if ($user_bean = UserModel::getByUuid($user_id)) {
        return $user_bean->serialize();
      }
      HTTP::setHeadersByCode(StatusCode::NOT_FOUND);
      return [];
    }
    throw new ValidationException("Validation failed, uuid not valid");
  }

  public function getAll(): array|object
  {
    return UserModel::get_all();
  }

  public function update(mixed $user_data): array|object
  {
    $validation = new CustomValidation($user_data);

    if ($validation->validate_update()) {

      $user_uuid = $user_data->uuid;
      $user_entity = new UserEntity();
      if (!empty($user_data->firstname)) {
        $user_entity->setFirstname($user_data->firstname);
      }
      if (!empty($user_data->lastname)) {
        $user_entity->setLastname($user_data->lastname);
      }
      if (!empty($user_data->phone)) {
        $user_entity->setPhone($user_data->phone);
      }

      $valid = $updated_user = UserModel::updateByUuid($user_uuid, $user_entity);
      if (!$valid) {
        Http::setHeadersByCode(StatusCode::NOT_FOUND);
        return [];
      }
      return $updated_user;
    }
    throw new ValidationException("Validation failed, wrong input data");
  }

  public function remove(string $user_id): array|object
  {
    $validation = new CustomValidation($user_id);
    if ($validation->validate_uuid()) {
      $valid = $delete_user = UserModel::deleteByUuid($user_id);
      if (!$valid) {
        Http::setHeadersByCode(StatusCode::NOT_FOUND);
        return ["error" => "User not found"];
      }
      return ["data" => $delete_user];
    }
    throw new ValidationException("Validation failed, uuid not valid");
  }

  public function login(mixed $user_data)
  {
    $validation = new CustomValidation($user_data);
    if ($validation->validate_login()) {
      return "Passt";
    }
    throw new ValidationException("Validation failed, incorrect email or password");
  }

}