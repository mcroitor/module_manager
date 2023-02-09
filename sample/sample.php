<?php

include_once __DIR__ . "/../src/mc/filesystem.php";
include_once __DIR__ . "/../src/mc/repository.php";

use mc\repository;

$git = new repository([
    repository::REPOSITORY => "database",
    repository::BRANCH => "main",
    repository::USER => "mcroitor",
]);

mkdir(__DIR__ . "/../modules/");

echo $git->url();

$git->download(__DIR__ . "/../modules/database");
