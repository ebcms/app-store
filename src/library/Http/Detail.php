<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Model\Server;
use Ebcms\RequestFilter;
use Ebcms\Template;

class Detail extends Common
{
    public function get(
        Server $server,
        RequestFilter $input,
        Template $template
    ) {
        $data = [];
        $data['server'] = $server;
        $res = $server->query('/detail', [
            'plugin_name' => $input->get('plugin_name'),
        ]);
        if ($res['status'] != 200) {
            return $this->failure($res['message']);
        }
        $data['plugin'] = $res['data'];
        return $template->renderFromFile('detail@ebcms/store', $data);
    }
}
