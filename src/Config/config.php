<?php

namespace Ls\Api\Config;

use Whoops\Handler\JsonResponseHandler as WhoopsJsonResponseHandler;
use Whoops\Run as WhoopsRun;


$whoops = new WhoopsRun();
$whoops->pushHandler(new WhoopsJsonResponseHandler());
$whoops->register();