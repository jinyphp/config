<?php

namespace Jiny\Config\Drivers;

/**
 * jiny 
 * php 설정파일 드라이버
 *
 */
class PHP
{
    private $Config;

    public function __construct($conf)
    {
        // \TimeLog::set(__CLASS__."가 생성이 되었습니다.");

        // 의존성 주입
        // 호출된 config 클래스의 인스턴스르 저장합니다.
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
        // \TimeLog::set(__METHOD__);
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