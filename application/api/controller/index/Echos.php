<?php


namespace app\api\controller\index;


use think\Controller;

class Echos extends Controller
{
    public function echos($name = null, $phone = null, $body = null)
    {
echo 1;die;

        if (empty($name) || empty($phone) || empty($body)) {
            $this->error('信息不完整');
        }
        if (!preg_match("/^1[34578]\d{9}$/", $phone)) {
            $this->error('手机号不符合格式');
        }


        $ip = request()->ip();
        if (db('cms_echo')->where('phone', $phone)->count() > 0) {
            $this->error('留言成功');
        } else {
            $data = [
                'ip' => $ip,
                'name' => $name,
                'phone' => $phone,
                'body' => $body,
                'create_time' => date('Y-m-d H:i:s', time())
            ];
            $res = db('cms_echo')->insert($data);
            if ($res) {
                $this->success('留言成功');
            } else {
                $this->error('留言失败');

            }
        }
    }

}