<?php
/**
 * Hook를 검색 처리합니다.
 */
namespace Jiny\Config\Http\Livewire;

trait Hook
{
    // 후크 메소드가 존재하는지 검사합니다.
    private function isHook($name)
    {
        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, $name)) {
                // 존재하는 경우 컨트롤러 객체를 반환합니다.
                return $controller;
            }
        }

        return null;
    }
}
