<?php

namespace Ls\Api\ORM;

use RedBeanPHP\R;

class ApiTokenModel
{
  private const TABLE_NAME = 'jwtsecretkeys';

  public static function saveSecretKey(string $jwt_key): void
  {
    $token_bean = R::dispense(self::TABLE_NAME);
    $token_bean->secret_key = $jwt_key;

    R::store($token_bean);
    R::close();
  }

  public static function getSecretKey(int $id = 1): ?string
  {
    $jwt_secret_key = R::load(self::TABLE_NAME, $id);

    return $jwt_secret_key?->secret_key;
  }
}