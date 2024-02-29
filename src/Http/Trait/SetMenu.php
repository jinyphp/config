<?php
namespace Jiny\Config\Http\Trait;

trait SetMenu
{
    private $MENU_PATH = "menus";

    protected function setUserMenu($user)
    {
        if(function_exists('xMenu')) {
            // 사용자가 있는 경우 사용자 메뉴 적용

            if(isset($user->menu)) {
                xMenu()->setPath($user->menu);
            } else {
                // Actions에서 적용한 메뉴
                if(isset($this->actions['menu'])) {
                    $menuid = _getKey($this->actions['menu']);
                    xMenu()->setPath($this->MENU_PATH . DIRECTORY_SEPARATOR . $menuid . ".json");
                }
            }

        }
    }
}
