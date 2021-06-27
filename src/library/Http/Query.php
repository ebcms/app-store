<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Model\Server;
use Ebcms\Request;

class Query extends Common
{
    public function get(
        Request $request,
        Server $server
    ) {
        $res = $server->query('/' . $request->get('api'), (array)$request->get('params'));
        if ($res['status'] == 200) {
            return $this->success('获取成功', '', $res['data']);
        } else {
            return $this->failure($res['message']);
        }
    }
}
