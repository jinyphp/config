<?php

namespace Jiny\Config\Drivers;

class INI
{
    private $Config;

    public function __construct($conf)
    {
        echo "<hr>";
        echo __CLASS__."를 생성합니다.<br>";
        $this->Config = $conf;
    }

    // ini 설정파일을 로드합니다.
    public function loadINI($name, $path=NULL)
    {
        if ($name) {
            if ($path) {
                $filename = $path.$name.".ini";
            } else {
                $filename = $name."ini";
            }

            if (\file_exists($filename)) {
                $str = file_get_contents($filename);           
                return \parse_ini_string($str);       
            } else {
                // 파일이 없습니다.

            }            
        } else {
            // 이름이 없습니다.
        }

    }
}