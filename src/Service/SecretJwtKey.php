<?php

namespace Ls\Api\Service;

use Ls\Api\ORM\ApiTokenModel;

class SecretJwtKey
{
  public static function getSecretJwtKey(): ?string
  {
    $jwt_key = ApiTokenModel::getSecretKey();
    if (!$jwt_key) {
      $secret_key = hash("sha256", (string)time());
      ApiTokenModel::saveSecretKey($secret_key);
      return $secret_key;
    }
    return $jwt_key;
  }
}