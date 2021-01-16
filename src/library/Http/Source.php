<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Model\Server;
use Ebcms\App;
use Ebcms\RequestFilter;
use Ebcms\Session;

class Source extends Common
{
    public function get(
        App $app,
        RequestFilter $input,
        Server $server,
        Session $session
    ) {
        $version = '';
        $json_file = $app->getAppPath() . '/plugin/' . $input->get('plugin_name') . '/plugin.json';
        if (
            file_exists($json_file) &&
            file_exists($app->getAppPath() . '/config/plugin/' . $input->get('plugin_name') . '/install.lock')
        ) {
            $plugin_info = json_decode(file_get_contents($json_file), true);
            $version = $plugin_info['version'];
        }

        $res = $server->query('/source', [
            'ticket' => $input->get('ticket'),
            'version' => $version,
        ]);

        if ($res['status'] == 200) {
            $session->set('plugin', $res['data']);
            return $this->success('获取成功！', '', $res['data']);
        } else {
            return $this->failure($res['message']);
        }
    }
}
