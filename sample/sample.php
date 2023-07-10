<?php

include_once __DIR__ . "/../src/mc/filesystem.php";
include_once __DIR__ . "/../src/mc/repository.php";

use mc\repository;

$git = new repository([
    repository::REPOSITORY => "database",
    repository::BRANCH => "main",
    repository::USER => "mcroitor",
    repository::SOURCE => "src"
]);

// mkdir(__DIR__ . "/../modules/");

echo $git->url();

$archive = $git->download();
$git->unpack($archive, __DIR__ . "/../modules/database");
