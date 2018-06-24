<?php

namespace Jiny\Config\Drivers;

/**
 * jiny 
 * ymal 설정파일 드라이버
 *
 */
class Yaml
{
    private $Config;

    public function __construct($conf)
    {
        // \TimeLog::set(__CLASS__."가 생성이 되었습니다.");

        // 의존성 주입
        // 호출된 config 클래스의 인스턴스르 저장합니다.
        $this->Config = $conf;
    }

    public function loadYaml($name, $path=NULL)
    {
        // \TimeLog::set(__METHOD__);
        if ($name) {
            if ($path) {
                $filename = $path.$name.".yml";
            } else {
                $filename = $name."yml";
            }

            if (\file_exists($filename)) {
                $string = file_get_contents($filename);
                return $this->parser($string);  

            } else {
                echo "Yaml 파일($path $name)이 없습니다.<br>"; 

            }            
        } else {
            // 이름이 없습니다.
        }
    }

    public function parser($string)
    {   
        // \TimeLog::set(__METHOD__);
        if ($string) {
            //return \Symfony\Component\Yaml\Yaml::parse($string);
            return \Jiny\Config\Yaml::parse($string);
        }        
    }

    /**
     * 
     */
    public function dump($data)
    {
        if (is_array($data) && empty($data)) {
            return '';
        }

        //return \Symfony\Component\Yaml\Yaml::dump($data);
        return \Jiny\Config\Yaml::dump($data);
    }

    /**
     * 
     */
}