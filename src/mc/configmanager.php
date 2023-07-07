<?php

namespace mc;

class ConfigManager
{
    /// config elements
    public const ORIGIN         = "origin";
    public const REPOSITORY     = "repository";
    public const BRANCH         = "branch";
    public const USER           = "user";
    public const TOKEN          = "token";
    public const SOURCE         = "source";
    public const DESTINATION    = "destination";

    /// messages
    public const FAIL_FILE_EXISTS = "config file exists";
    public const FAIL_REPO_DEFINITION = "error in repo definition";
    public const OK = "ok";

    private $config_file = "modules.json";
    private $modules = [];

    public function __construct($config_file)
    {
        $this->config_file = $config_file;
        if (file_exists($this->config_file)) {
            $this->read();
        }
    }

    private function read()
    {
        $json = file_get_contents($this->config_file);
        $this->modules = json_decode($json);
    }

    private function write()
    {
        $json = json_encode($this->modules);
        file_put_contents($this->config_file, $json);
    }

    public function init()
    {
        if (file_exists($this->config_file)) {
            return self::FAIL_FILE_EXISTS;
        }
        $this->modules = [];
        $this->write();
        return self::OK;
    }

    public function truncate()
    {
        $this->modules = [];
        $this->write();
        return self::OK;
    }

    public function add(array $repo)
    {
        if (empty($repo[self::REPOSITORY]) || empty($repo[self::USER])) {
            return self::FAIL_REPO_DEFINITION;
        }
        $this->modules[] = $repo;
        $this->write();
        return self::OK;
    }

    public function update(array $repo)
    {
        $result = $this->remove($repo);
        if ($result === self::FAIL_REPO_DEFINITION) {
            return self::FAIL_REPO_DEFINITION;
        }
        return $this->add($repo);
    }

    public function remove(array $repo)
    {
        if (empty($repo[self::REPOSITORY]) || empty($repo[self::USER])) {
            return self::FAIL_REPO_DEFINITION;
        }
        foreach ($this->modules as $key => $value) {
            if (
                $value[self::REPOSITORY] === $repo[self::REPOSITORY] &&
                $value[self::USER] === $repo[self::USER]
            ) {
                unset($this->modules[$key]);
            }
        }
        $this->write();
        return self::OK;
    }

    public function modules() {
        return $this->modules;
    }
}
