<?php

namespace Jiny\Config\Drivers;

/**
 * ini 설정파일 드라이버
 */
class INI
{
    private $Config;

    /**
     * 의존성 주입
     */
    public function __construct($conf)
    {
        // 호출된 config 클래스의 인스턴스르 저장합니다.
        $this->Config = $conf;
    }

    // ini 설정파일을 로드합니다.
    public function loadINI($name, $path=NULL)
    {
        // \TimeLog::set(__METHOD__);
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