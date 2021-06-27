<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Model\Server;
use Ebcms\Request;
use Ebcms\Template;

class Detail extends Common
{
    public function get(
        Server $server,
        Request $request,
        Template $template
    ) {
        $data = [];
        $data['server'] = $server;
        $res = $server->query('/detail', [
            'plugin_name' => $request->get('plugin_name'),
        ]);
        if ($res['status'] != 200) {
            return $this->failure($res['message']);
        }
        $data['plugin'] = $res['data'];
        return $template->renderFromFile('detail@ebcms/store', $data);
    }
}
