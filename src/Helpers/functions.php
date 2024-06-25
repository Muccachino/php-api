<?php

namespace Ls\Api\Helpers;


/**
 * Returns Data in JSON Format
 * @param mixed $data
 * @return void
 */
function response(mixed $data): void
{
  echo json_encode($data);
}