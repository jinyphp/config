<?php

namespace Jiny\Config;

/**
 * 환경설정 추상화 클래스
 */
abstract class ConfigAbstract 
{
    /**
     * 설정된 값을 통합 저장되는 배열입니다.
     */
    protected $_config=[];

    /**
     * 설정 파일의 목록을 가지고 있습니다.
     * ./conf/ 디렉토리의 파일들을 읽어 옵니다.
     * Key 값는 설정파일의 이름으로 처리됩니다.
     * 파일명이 '_'로 시작되는 경우 처리되지 않습니다. 
     */
    protected $_load=[];
    
}