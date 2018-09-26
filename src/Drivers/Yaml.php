<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Config\Drivers;

/**
 * ymal 설정파일 드라이버
 */
class Yaml extends \Jiny\Config\Driver
{
    /**
     * 의존성 주입
     */
    public function __construct($conf)
    {
        // 호출된 config 클래스의 인스턴스르 저장합니다.
        $this->Config = $conf;
    }

    public function load($name, $path=NULL)
    {
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
                //echo "Yaml 파일($path $name)이 없습니다.<br>"; 
            }

        } else {
            // 이름이 없습니다.
        }
    }

    /**
     * Yaml 데이터를 파싱합니다.
     */
    public function parser($string)
    {   
        if ($string) {
            // 심포니 Yaml 부분 컨버팅
            return \Jiny\Config\Yaml\Yaml::parse($string);
        }        
    }

    /**
     * 배열 데이터를 Yaml 포맷으로 전환합니다. 
     */
    public function dump($data)
    {
        if (is_array($data) && empty($data)) {
            return '';
        }

        // 심포니 Yaml 부분 컨버팅
        return \Jiny\Config\Yaml\Yaml::dump($data);
    }

    /**
     * 
     */
}