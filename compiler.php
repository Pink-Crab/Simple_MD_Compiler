<?php

declare(strict_types=1);

use Silly\Application;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application('PinkCrab Simple Markdown Compiler', '0.0.1');
$app->command('compile', new PinkCrab\Simple_MD_Compiler\Action\Compile);

$app->run();
