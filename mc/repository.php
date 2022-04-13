<?php

namespace mc;

/**
 * repository downloader
 */
class repository {
    public const URL            = "url";
    public const REPOSITORY     = "repository";
    public const BRANCH         = "branch";
    public const USER           = "user";
    public const TOKEN          = "token";
    public const DESTINATION    = "destination";

    private string $url         = "https://github.com/";
    private string $repository;
    private string $branch      = "main";
    private string $user;
    private string $token       = "";
    private string $destination = "./modules/";

    public function __construct(array $config) {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    public function url() {
        return $this->url;
    }

    public function repository() {
        return $this->repository;
    }

    public function branch() {
        return $this->branch;
    }

    public function link() {
        // https://github.com/mcroitor/database/archive/refs/heads/main.zip
        return "{$this->url}{$this->user}/{$this->repository}/archive/refs/heads/{$this->branch}.zip";
    }

    public function download(string $destination = "") {
        if(empty($destination)) {
            $destination = $this->destination;
        }

        $ch = curl_init($this->link());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if (!empty($this->token)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: token " . $this->token
            ));
        }

        $zipfile = curl_exec($ch);
        curl_close($ch);

        // $zipfile = tempnam(sys_get_temp_dir(), "github");
        file_put_contents($destination . "_ok.zip", $zipfile);

        $zip = new \ZipArchive();
        $zip->open($destination . "_ok.zip");
        $zip->extractTo($destination);
        $zip->close();

        unlink($destination . "_ok.zip");

        rename("{$destination}/{$this->repository}-{$this->branch}", "{$destination}/{$this->repository}");
    }

    public function drop(){
        $files = glob($this->destination . "*");

        foreach ($files as $file) {
            self::remove($file);
        }
    }

    private static function remove(string $path) {
        if(is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                self::remove($path . DIRECTORY_SEPARATOR . $file);
            }
            rmdir($path);
        } else {
            unlink($path);
        }
    }
}