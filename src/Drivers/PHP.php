<?php

namespace Jiny\Config\Drivers;

class PHP
{
    private $Config;

    public function __construct($conf)
    {
        //echo "<hr>";
        //echo __CLASS__."를 생성합니다.<br>";
        $this->Config = $conf;
    }
    
    /**
     * PHP Return 배열로 된 설정값을 읽어 옵니다.
     * [
     *  "name"=>"aaa"
     * ]
     */
    public function loadPHP($name, $path=NULL)
    {
        if ($name) {
            if ($path) {
                $filename = $path.$name.".php";
            } else {
                $filename = $name.".php";
            }

            if (file_exists($filename)) {
                //Debug::out(">>>".$filename." 설정값을 읽어 옵니다.");
                return include ($filename);
            } else {
                // 파일이 존재하지 않습니다.
                //Debug::out(">>>파일이 존재하지 않습니다.");
            }            
        } else {
            // 파일 이름이 없습니다.
        }
    }
}