<?php

namespace mc;

/**
 * repository downloader
 */
class repository
{
    public const ORIGIN         = "origin";
    public const REPOSITORY     = "repository";
    public const BRANCH         = "branch";
    public const USER           = "user";
    public const TOKEN          = "token";
    public const SOURCE         = "source";
    public const DESTINATION    = "destination";

    private const TMPDIR        = "./__tmp__";

    private string $origin      = "https://github.com/";
    private string $repository;
    private string $branch      = "main";
    private string $user;
    private string $token       = "";
    private string $source;
    private string $destination = "./modules";

    /**
     * create repository downloader from $config array
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }

        $this->source = $this->repository() . "-" . $this->branch();
        if(!empty($config["source"])){
            $this->source = \mc\filesystem::implode($this->source(), $config["source"]);
        }
    }

    /**
     * returns repository host / origin
     * @return string
     */
    public function origin()
    {
        return $this->origin;
    }

    /**
     * returns repository name
     * @return string
     */
    public function repository()
    {
        return $this->repository;
    }

    /**
     * returns repository branch
     * @return string
     */
    public function branch()
    {
        return $this->branch;
    }

    /**
     * returns user / organisation name, repository owner
     * @return string
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * returns source for copy
     * @return string
     */
    public function source()
    {
        return $this->source;
    }

    /**
     * returns path for repository storing.
     * @return string
     */
    public function destination()
    {
        return $this->destination;
    }

    /**
     * returns URL to the archived repository branch
     * @return string
     */
    public function url()
    {
        // https://github.com/mcroitor/database/archive/refs/heads/main.zip
        return "{$this->origin}{$this->user}/{$this->repository}/archive/refs/heads/{$this->branch}.zip";
    }

    /**
     * download repository branch to the $destination folder
     * @param string $destination
     */
    public function download(string $destination = "")
    {
        if (!file_exists(self::TMPDIR)) {
            mkdir(self::TMPDIR);
        }

        if (empty($destination)) {
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
        $tmpname = \mc\filesystem::implode(self::TMPDIR, "{$this->user()}_{$this->repository()}.zip");
        file_put_contents($tmpname, $zipfile);

        $zip = new \ZipArchive();
        $zip->open($tmpname);
        $tmpdir = \mc\filesystem::implode(self::TMPDIR, "{$this->user()}_{$this->repository()}");
        $zip->extractTo($tmpdir);
        $zip->close();

        \mc\filesystem::copy(\mc\filesystem::implode($tmpdir, $this->source()), $destination);

        \mc\filesystem::unlink($tmpdir);
    }

    /**
     * remove content of $this->destination folder
     */
    public function drop()
    {
        $files = glob($this->destination . "*");

        foreach ($files as $file) {
            \mc\filesystem::unlink($file);
        }
    }

}
