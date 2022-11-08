<?php

include_once __DIR__ . "/mc/filesystem.php";
include_once __DIR__ . "/mc/repository.php";

$longopts = [
    "help",
    "info",
    "install",
    "reinstall",
    "drop",
    "entrypoint::",
    "config::"
];

$config_file = "config.json";
$entrypoint = "./modules/entrypoint.php";
$debug = false;

/**
 * print usage
 */
function usage () {
    echo "Usage: " . __FILE__ . " [options]" . PHP_EOL;
    echo "Options:" . PHP_EOL;
    echo "  --help                 Show this help" . PHP_EOL;
    echo "  --info                 Print module configuration" . PHP_EOL;
    echo "  --install              Install libraries" . PHP_EOL;
    echo "  --reinstall            Reinstall libraries" . PHP_EOL;
    echo "  --drop                 Drop all libraries" . PHP_EOL;
    echo "  --entrypoint=<path>    Path to the entrypoint file" . PHP_EOL;
    echo "  --config=<path>        Path to the config file" . PHP_EOL;
}

/**
 * print module configuration
 */
function info (string $config_file) {
    if(!file_exists($config_file)) {
        echo "Config file not found" . PHP_EOL;
        return;
    }
    $config = json_decode(file_get_contents($config_file), true);
    echo "module configuration:" . PHP_EOL;
    foreach ($config as $module_config) {
        $repo = new mc\repository($module_config);
        echo "\t" . $repo->user() . "/" . $repo->repository() 
            . " => " . $repo->destination() . " : " . $repo->url() . PHP_EOL;
    }
}

/**
 * install modules defined in the config file
 */
function install (string $config_file) {
    if(!file_exists($config_file)) {
        echo "Config file not found" . PHP_EOL;
        return;
    }
    $config = json_decode(file_get_contents($config_file), true);

    foreach ($config as $module_config) {
        echo "Installing {$module_config['user']}/{$module_config['repository']} ... ";
        $path = empty($module_config["destination"]) ? "./modules" : $module_config["destination"];
        // check if destination folder exists, create it
        if(!file_exists($path)) {
            mkdir($path);
        }

        $path .= DIRECTORY_SEPARATOR . $module_config["repository"];
        // if repository folder exists, warn
        if(file_exists($path)){
            echo PHP_EOL;
            echo "[warn] module {$module_config['repository']} exists.";
            echo " Did you want to reinstall it? SKIP MODULE" . PHP_EOL;
            continue;
        }

        // download
        $repo = new mc\repository($module_config);
        $repo->download();
        echo "[OK]" . PHP_EOL;
    }
}

/**
 * drop modules defined in config file
 */
function drop(string $config_file){
    if(!file_exists($config_file)) {
        echo "Config file not found" . PHP_EOL;
        return;
    }

    $config = json_decode(file_get_contents($config_file), true);

    foreach ($config as $module_config) {
        echo "Dropping {$module_config['user']}/{$module_config['repository']} ... ";
        $manager = new mc\repository($module_config);
        $manager->drop();
        echo "[OK]" . PHP_EOL;
    }
}

/**
 * reinstall modules defined in the config file
 */
function reinstall (string $config_file) {
    echo "Reinstalling modules ... " . PHP_EOL;
    drop($config_file);
    install($config_file);
    echo "Done." . PHP_EOL;
}

/**
 * create entrypoint.php file
 */
function entrypoint(string $config_file, string $entrypoint = "entrypoint.php") {
    $result = "<?php" . PHP_EOL . PHP_EOL;

    if(!file_exists($config_file)) {
        echo "Config file not found" . PHP_EOL;
        return;
    }

    $config = json_decode(file_get_contents($config_file), true);

    foreach($config as $module_config){
        // check if entry point is defined in the module config
        if(empty($module_config["entrypoint"])){
            continue;
        }
        
        // check if file exists
        $path = empty($module_config["destination"]) ? "./modules" : $module_config["destination"];
//        $path .= "/" . $module_config["repository"];
        $path .= "/" . $module_config["entrypoint"];
        if(!file_exists($path)){
            echo "[warn] entry point {$path} for {$module_config['repository']} is missing" . PHP_EOL;
            continue;
        }

        // add include_once to result
        $result .= "include_once '{$path}';" . PHP_EOL;
    }

    file_put_contents($entrypoint, $result);
}

$opts = getopt("", $longopts);

if(isset($opts["help"]) || !(isset($opts["install"]) || isset($opts["info"]) || isset($opts["reinstall"]) || isset($opts["drop"]))) {
    usage();
    exit(0);
}

if(isset($opts["config"])) {
    $config_file = $opts["config"];
}

if(isset($opts["entrypoint"])) {
    $entrypoint = $opts["entrypoint"];
}

if(isset($opts["info"])) {
    info($config_file);
    exit(0);
}

if(isset($opts["install"])) {
    install($config_file);
    entrypoint($config_file, $entrypoint);
    exit(0);
}

if(isset($opts["reinstall"])) {
    reinstall($config_file);
    entrypoint($config_file, $entrypoint);
    exit(0);
}

if(isset($opts["drop"])) {
    drop($config_file);
    if(file_exists($entrypoint)){
        unlink($entrypoint);
    }
    exit(0);
}
