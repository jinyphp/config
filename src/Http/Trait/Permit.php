<?php
namespace Jiny\Config\Http\Trait;

use Illuminate\Support\Facades\Auth;

trait Permit
{
    public $permit;
    public $popupPermit = false;

    public $permitMessage;

    public function isPermit($name)
    {
        if($name) {
            if(isset($this->permit[$name])) {
                if($this->permit[$name]) return true;
            }
        }

        return false;
    }

    public function permitCheck()
    {
        // 인증된 사용자
        if( $user = Auth::user() ) {
            if($this->isRole()) {
                //dd($this->actions['roles']);

                if (function_exists("authRoles")) {
                    $Role = authRoles($user->id);
                    $this->permit = $Role->permitAll($this->actions);

                } else {
                    // jiny/auth 모듈이 설치되어 있지 않는 경우,
                    $this->permitAllow(); // 모두 허용
                }
            } else {
                // 권한설정 미적용시
                $this->permitAllow(); // 모두 허용
            }
        } else
        // 미인증 요청.
        {
            if($this->isRole()) {
                $this->permitDeny();

                foreach($this->actions['roles'] as $role)
                {
                    if(isset($role['permit']) && $role['permit']) {
                        if(isset($role['create']) && $role['create']) $this->permit['create'] = true;
                        if(isset($role['read']) && $role['read']) $this->permit['read'] = true;
                        if(isset($role['update']) && $role['update']) $this->permit['update'] = true;
                        if(isset($role['delete']) && $role['delete']) $this->permit['delete'] = true;
                    }
                }
            } else {
                // 권한설정 미적용시,
                // 모두 허용
                $this->permitAllow();
            }
        }

        //dd($this->permit);
        return $this->permit;
    }

    private function isRole()
    {
        if(isset($this->actions['role']) && $this->actions['role']) {
            return true;
        }

        return false;
    }

    private function permitAllow()
    {
        $this->permit = [
            'create' => true,
            'read' => true,
            'update' => true,
            'delete' => true,
        ];
    }

    private function permitDeny()
    {
        $this->permit = [
            'create' => false,
            'read' => false,
            'update' => false,
            'delete' => false,
        ];
    }

    // 권한없음 경고창
    public function popupPermitOpen()
    {
        $this->popupPermit = true;
    }

    public function popupPermitClose()
    {
        $this->popupPermit = false;
    }

    protected function permitUpdate()
    {
        if($this->permit['update']) {
            return true;
        } else {
            $this->permitMessage = "수정 권한이 없습니다.";
            return false;
        }
    }
}
