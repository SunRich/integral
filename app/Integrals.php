<?php
/**
 * Created by PhpStorm.
 * User: liuwenfu
 * Date: 2017/5/4
 * Time: 下午2:09
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Integrals extends Model
{
    protected $table = 'user_integrals';

    public $timestamps = false;

    /**
     * 添加数据
     * @param $userId
     * @param $integral
     * @param $content
     * @return mixed
     */
    public function addOneRecord($userId, $integral, $content)
    {
        return $this::insert([
            'user_id' => $userId,
            'integral' => $integral,
            'content' => $content,
        ]);
    }

    /**
     * 获取积分总数
     * @param $data
     * @return mixed
     */

    public function getUserIntegralCount($data)
    {
        $map[] = ['user_id', '=', $data['userId']];
        $map = $this->getTypeWhere($map, $data['type']);
        return abs($this::where($map)->whereBetween('time', [$data['startTime'], $data['endTime']])->sum('integral'));
    }

    /**
     * 获取积分详情
     * @param $data
     * @param $pageSize
     * @return mixed
     */
    public function getUserIntegralInfo($data, $pageSize)
    {
        $map[] = ['user_id', '=', $data['userId']];
        $map = $this->getTypeWhere($map, $data['type']);
        return $this::where($map)->whereBetween('time', [$data['startTime'], $data['endTime']])->paginate($pageSize);
    }

    public function getTypeWhere($where, $type)
    {
        switch ($type) {
            case 'add':
                $where[] = ['integral', '>', 0];
                break;
            case 'expend':
                $where[] = ['integral', '<', 0];
                break;
        }
        return $where;
    }
}