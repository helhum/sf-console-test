<?php
require __DIR__ . '/vendor/autoload.php';

$app = new \Symfony\Component\Console\Application();
$app->add(new \Helhum\SfTest\HiddenInputCommand());
$app->run();
