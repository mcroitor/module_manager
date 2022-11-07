<?php

namespace mc;

/**
 * repository downloader
 */
class repository {
    public const ORIGIN         = "origin";
    public const REPOSITORY     = "repository";
    public const BRANCH         = "branch";
    public const USER           = "user";
    public const TOKEN          = "token";
    public const DESTINATION    = "destination";

    private string $origin      = "https://github.com/";
    private string $repository;
    private string $branch      = "main";
    private string $user;
    private string $token       = "";
    private string $destination = "./modules/";

    /**
     * create repository downloader from $config array
     * @param array $config
     */
    public function __construct(array $config) {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * returns repository host / origin
     * @return string
     */
    public function origin() {
        return $this->origin;
    }

    /**
     * returns repository name
     * @return string
     */
    public function repository() {
        return $this->repository;
    }

    /**
     * returns repository branch
     * @return string
     */
    public function branch() {
        return $this->branch;
    }

    /**
     * returns user / organisation name, repository owner
     * @return string
     */
    public function user() {
        return $this->user;
    }

    /**
     * returns path for repository storing.
     * @return string
     */
    public function destination() {
        return $this->destination;
    }

    /**
     * returns URL to the archived repository branch
     * @return string
     */
    public function url() {
        // https://github.com/mcroitor/database/archive/refs/heads/main.zip
        return "{$this->origin}{$this->user}/{$this->repository}/archive/refs/heads/{$this->branch}.zip";
    }

    /**
     * download repository branch to the $destination folder
     * @param string $destination
     */
    public function download(string $destination = "") {
        if(empty($destination)) {
            $destination = $this->destination;
        }

        $ch = curl_init($this->url());
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

    /**
     * remove content of $this->destination folder
     */
    public function drop(){
        $files = glob($this->destination . "*");

        foreach ($files as $file) {
            self::remove($file);
        }
    }

    /**
     * helper method, remove folder with content
     * @param string $path
     */
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
