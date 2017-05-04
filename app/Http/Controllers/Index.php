<?php
/**
 * Created by PhpStorm.
 * User: liuwenfu
 * Date: 2017/5/4
 * Time: 下午2:39
 */
namespace App\Http\Controllers;

use App\Integrals;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function counts($userId, $type,$startTime, $endTime)
    {
        $return = $this->restInit();
        if ($userId > 0) {
            $Integrals = new Integrals();
            $data = [
                'userId' => $userId,
                'type' => $type,
                'startTime' => $startTime,
                'endTime' => $endTime,
            ];
            $return = [
                'code' => 200,
                'result' => $Integrals->getUserIntegralCount($data)
            ];
        } else {
            $return['message'] = 'empty userId';
        }
        return response()->json($return);
    }

    public function infos($userId, $type,$startTime, $endTime)
    {
        $return = $this->restInit();
        if ($userId > 0) {
            $Integrals = new Integrals();
            $data = [
                'userId' => $userId,
                'type' => $type,
                'startTime' => $startTime,
                'endTime' => $endTime,
            ];
            $return = [
                'code' => 200,
                'result' => $Integrals->getUserIntegralInfo($data, 10)
            ];
        } else {
            $return['message'] = 'empty userId';
        }
        return response()->json($return);
    }

    public function add(Request $request)
    {
        $userId = $request->input('userId', 0);
        $integral = $request->input('integral', 0);
        $content = $request->input('content', '签到增加积分');
        $return = $this->restInit();
        if ($userId > 0 && $integral > 0) {
            $Integrals = new Integrals();
            if ($id = $Integrals->addOneRecord($userId, $integral, $content)) {
                $return = [
                    'code' => 200,
                    'result' => $integral,
                    'message' => 'ok'
                ];
            }
        } else {
            $return['message'] = 'empty userId or integral';
        }
        return response()->json($return);
    }

    public function expend(Request $request)
    {
        $userId = $request->input('userId', 0);
        $integral = $request->input('integral', 0);
        $content = $request->input('content', '消耗积分');
        $return = $this->restInit();
        if ($userId > 0 && $integral > 0) {
            if ($this->checkIntegralLeft($userId, $integral)) {
                $Integrals = new Integrals();
                if ($Integrals->addOneRecord($userId, -$integral, $content)) {
                    $return = [
                        'code' => 200,
                        'result' => $integral,
                        'message' => 'ok'
                    ];
                }
            } else {
                $return['message'] = 'short of integral';//余额不足
            }
        } else {
            $return['message'] = 'empty userId or integral';
        }
        return response()->json($return);
    }


    private function checkIntegralLeft($userId, $integral)
    {
        $Integrals = new Integrals();
        if ($Integrals->getUserIntegralCount([
                'userId' => $userId,
                'type' => '',
                'startTime' => date('Y-01-01', time()),
                'endTime' => date('Y-m-d H:i:s', time())
            ]) > $integral
        ) {
            return true;
        }
        return false;
    }

    private function restInit()
    {
        return [
            'code' => 404,
            'message' => 'error'
        ];
    }
}