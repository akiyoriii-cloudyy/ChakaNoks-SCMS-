<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public $protocol   = 'smtp';
    public $SMTPHost   = 'smtp.gmail.com';
    public $SMTPUser   = 'chakanoks.system@gmail.com';
    public $SMTPPass   = 'vvivsynirqzlwgai';
    public $SMTPPort   = 465;
    public $SMTPCrypto = 'ssl';
    public $mailType   = 'html';
    public $charset    = 'utf-8';
    public $newline    = "\r\n";
    public $wordWrap   = true;

    public function __construct()
    {
        parent::__construct();

        // Load from .env (with "email." prefix)
        $this->protocol   = getenv('email.protocol') ?: $this->protocol;
        $this->SMTPHost   = getenv('email.SMTPHost') ?: $this->SMTPHost;
        $this->SMTPUser   = getenv('email.SMTPUser') ?: $this->SMTPUser;
        $this->SMTPPass   = getenv('email.SMTPPass') ?: $this->SMTPPass;
        $this->SMTPPort   = getenv('email.SMTPPort') ?: $this->SMTPPort;
        $this->SMTPCrypto = getenv('email.SMTPCrypto') ?: $this->SMTPCrypto;
        $this->mailType   = getenv('email.mailType') ?: $this->mailType;
        $this->charset    = getenv('email.charset') ?: $this->charset;
        $this->newline    = getenv('email.newline') ?: $this->newline;
    }
}
