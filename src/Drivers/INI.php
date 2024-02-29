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

use Jiny\Filesystem\File;

/**
 * ini 설정파일 드라이버
 */
class INI extends \Jiny\Config\Driver
{
    /**
     * 인스턴스 저장 프로퍼티
     */
    private static $Instance;

    /**
     * 싱글턴 인스턴스를 생성합니다.
     */
    public static function instance()
    {
        if (!isset(self::$Instance)) {
            // 자기 자신의 인스턴스를 생성합니다.
            self::$Instance = new self();
            return self::$Instance;
        }

        return self::$Instance;
    }

    /**
     * 의존성 주입
     */
    public function __construct($conf=null)
    {
        // 호출된 config 클래스의 인스턴스르 저장합니다.
        $this->Config = $conf;
    }

    // ini 설정파일을 로드합니다.
    public function load($name, $path=NULL)
    {
        if ($name) {
            if ($path) {
                $filename = File::path($path).File::DS.$name.".ini";
            } else {
                $filename = $name.".ini";
            }

            if ($str = File::read($filename)) {
                return \parse_ini_string($str);
            }

        } else {
            // 이름이 없습니다.
        }

    }

    /**
     *
     */
}
