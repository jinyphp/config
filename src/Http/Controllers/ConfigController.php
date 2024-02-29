<?php
namespace Jiny\Config\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;


use Jiny\Table\Http\Controllers\BaseController;
class ConfigController extends BaseController
{
    use \Jiny\Config\Http\Livewire\Permit;       // 권환
    use \Jiny\Config\Http\Controllers\SetMenu;   // 메뉴

    public function __construct()
    {
        parent::__construct();  // setting Rule 초기화
        $this->setVisit($this); // Livewire와 양방향 의존성 주입
    }

    public function index(Request $request)
    {
        // 사용자 인증정보 체크
        $user = Auth::user();
        if ($user) {
            // 메뉴 설정
            $this->setUserMenu($user);
        }


        // 권한
        $this->permitCheck();
        if($this->permit['read']) {
            $view = $this->checkEditView();


            return view($view,[
                'actions' => $this->actions,
                'request' => $request
            ]);
        }

        // 권한 접속 실패
        return view("jiny-config::error.permit",[
            'actions' => $this->actions,
            'request' => $request
        ]);
    }


    private function checkEditView()
    {
        // 메인뷰 페이지...
        if (isset($this->actions['view_main'])) {
            if (view()->exists($this->actions['view_main'])) {
                return $this->actions['view_main'];
            }
        }

        // config 수정
        return "jiny-config::config.layout";
    }

}
