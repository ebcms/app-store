<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Model\Server;
use Ebcms\RequestFilter;

class Query extends Common
{
    public function get(
        RequestFilter $input,
        Server $server
    ) {
        $res = $server->query('/' . $input->get('api'), (array)$input->get('params'));
        if ($res['status'] == 200) {
            return $this->success('获取成功', '', $res['data']);
        } else {
            return $this->failure($res['message']);
        }
    }
}
