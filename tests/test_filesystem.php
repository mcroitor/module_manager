<?php

function test(bool $expression, string $passed = "PASS", string $failed = "FAIL"): void {
    echo $expression ? $passed : $failed;
    echo PHP_EOL;
}

function info(string $message, $object = null): void {
    echo "[info] $message";
    if($object != null){
        echo " - " . json_encode($object);
    }
    echo PHP_EOL;
}

include_once __DIR__ . "/../mc/filesystem.php";

$unix_path = "/this/is/a/path";
$windows_path = "c:\\\\this\\is\\a\\path";
$custom_path = "c:\\\\this\\is/a/path";

info("TESTGROUP 1: path normalizing");

info("TEST 1.1:");
info("normalize path: " . $windows_path);
$result = mc\filesystem::normalize($windows_path);
info("normalized path: " . $result);

info("TEST 1.2:");
info("normalize path: " . $custom_path);
$result = mc\filesystem::normalize($custom_path);
info("normalized path: " . $result);

info("TESTGROUP 2: path parts");

info("initial path: " . $windows_path);
$result = \mc\filesystem::root($windows_path);
info("root: " . $result);

$result = \mc\filesystem::children($windows_path);
info("children: " . $result);