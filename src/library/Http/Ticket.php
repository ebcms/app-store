<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Model\Server;
use Ebcms\App;
use Ebcms\Router;
use Ebcms\RequestFilter;

class Ticket extends Common
{
    public function get(
        App $app,
        Router $router,
        Server $server,
        RequestFilter $input
    ) {
        $salt = md5(uniqid() . rand(100000000, 999999999));
        file_put_contents($app->getAppPath() . '/runtime/salt.tmp', $salt);

        $res = $server->query('/ticket', [
            'verify_url' => $router->buildUrl('/ebcms/store/verify'),
            'plugin_name' => $input->get('plugin_name'),
            'salt' => $salt,
        ]);
        if ($res['status'] == 200) {
            return $this->success('获取成功！', '', $res['data']);
        } else {
            return $this->failure($res['message']);
        }
    }
}
