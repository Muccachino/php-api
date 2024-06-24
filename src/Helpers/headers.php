<?php

use Ls\Api\Config\AllowCors;

(new AllowCors())->init();
header("Content-Type: application/json");