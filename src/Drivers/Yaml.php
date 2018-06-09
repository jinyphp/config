<?php

namespace Jiny\Config\Drivers;

use Webuni\FrontMatter\Processor\ProcessorInterface;
use Webuni\FrontMatter\Processor\YamlProcessor;

class Yaml
{
    private $Config;
    private $processor;

    public function __construct($conf)
    {
        echo "<hr>";
        echo __CLASS__."를 생성합니다.<br>";
        $this->Config = $conf;

        // Yaml 처리 인스턴스를 생성합니다.
        $this->processor = new YamlProcessor();
    }

    public function loadYaml($name, $path=NULL)
    {
        if ($name) {
            if ($path) {
                $filename = $path.$name.".yml";
            } else {
                $filename = $name."yml";
            }

            if (\file_exists($filename)) {
                $string = file_get_contents($filename);
                return $this->processor->parse($string);  

            } else {
                echo "Yaml 파일이 없습니다.<br>"; 

            }            
        } else {
            // 이름이 없습니다.
        }
    }
}