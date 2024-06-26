<?php

namespace Ls\Api\ORM;

use Exception;
use Ls\Api\Entity\User as UserEntity;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class UserModel
{
  const TABLE_NAME = 'users';

  public static function create(UserEntity $data): false|string
  {
    //TODO: Add Exception Handling
    $user_bean = R::dispense(self::TABLE_NAME);
    $user_bean->uuid = $data->getUuid();
    $user_bean->firstname = $data->getFirstname();
    $user_bean->lastname = $data->getLastname();
    $user_bean->email = $data->getEmail();
    $user_bean->password = $data->getPassword();
    $user_bean->phone = $data->getPhone();
    $user_bean->created_at = $data->getCreatedAt();

    try {
      $user_bean_id = R::store($user_bean);
    } catch (SQL $e) {
      return false;
    } finally {
      R::close();
    }

    $user_bean = R::load(self::TABLE_NAME, $user_bean_id);
    return $user_bean->uuid;
  }

  public static function get_all(): false|array
  {
    $user_beans = R::findAll(self::TABLE_NAME);
    $user_exists = $user_beans && count($user_beans);
    if (!$user_exists) {
      return [];
    }
    foreach ($user_beans as $bean) {
      unset($bean->id);
    }
    return $user_beans;
  }

  public static function deleteByUuid(string $uuid): bool
  {
    $user = self::getByUuid($uuid);
    try {
      R::trash($user);
      return true;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getByUuid(string $uuid): ?object
  {
    $user = R::findOne(self::TABLE_NAME, 'uuid = :uuid', ['uuid' => $uuid]);

    return $user;
  }

  public static function updateByUuid(string $uuid, UserEntity $updated_user): bool|object
  {
    $user_bean = self::getByUuid($uuid);

    if ($user_bean) {
      if ($updated_user->getFirstname()) {
        $user_bean->firstname = $updated_user->getFirstname();
      }
      if ($updated_user->getLastname()) {
        $user_bean->lastname = $updated_user->getLastname();
      }
      if ($updated_user->getPhone()) {
        $user_bean->phone = $updated_user->getPhone();
      }

      try {
        $user_bean_id = R::store($user_bean);
      } catch (SQL $e) {
        return false;
      } finally {
        R::close();
      }

      $updated_user_bean = R::findOne(self::TABLE_NAME, 'id = :id', ['id' => $user_bean_id]);
      unset($updated_user_bean->id);
      return $updated_user_bean;
    }
    return false;
  }
}