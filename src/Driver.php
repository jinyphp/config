<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Config;

/**
 * 환경설정 추상화 클래스
 */
abstract class Driver
{
    private $Config;

    /**
     * 파일을 읽는 추상메소드 입니다.
     */
    abstract public function load($name, $path=NULL);
}