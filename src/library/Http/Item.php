<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Model\Server;
use Ebcms\Request;
use Ebcms\Template;

class Item extends Common
{
    public function get(
        Request $request,
        Server $server,
        Template $template
    ) {
        $data = [];
        $res = $server->query('/detail', [
            'plugin_name' => $request->get('plugin_name'),
        ]);
        if ($res['status'] != 200) {
            return $this->failure($res['message']);
        }
        $data['plugin'] = $res['data'];
        $installed = $server->getInstalled();
        if (isset($installed[$request->get('plugin_name')])) {
            $data['type'] = 'upgrade';
        } else {
            $data['type'] = 'install';
        }
        return $template->renderFromFile('item@ebcms/store', $data);
    }
}
