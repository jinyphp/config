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
 * php 설정파일 드라이버
 */
class PHP extends \Jiny\Config\Driver
{
    /**
     * 의존성 주입
     */
    public function __construct($conf)
    {
        // 호출된 config 클래스의 인스턴스르 저장합니다.
        $this->Config = $conf;
    }
    
    /**
     * PHP Return 배열로 된 설정값을 읽어 옵니다.
     * [
     *  "name"=>"aaa"
     * ]
     */
    public function load($name, $path=NULL)
    {
        if ($name) {
            if ($path) {
                $filename = $path.$name.".php";
            } else {
                $filename = $name.".php";
            }

            if (file_exists($filename)) {
                return include ($filename);
            } else {
                // 파일이 존재하지 않습니다.
            }            
        } else {
            // 파일 이름이 없습니다.
        }
    }

    /**
     * 
     */
}