<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Traits\DirTrait;
use Ebcms\Session;
use Throwable;

class Download extends Common
{
    use DirTrait;

    public function get(
        Session $session
    ) {
        $plugin = $session->get('plugin');
        try {
            $content = file_get_contents($plugin['source']);
        } catch (Throwable $th) {
            return $this->failure('文件下载失败！');
        }
        if (md5($content) != $plugin['md5']) {
            return $this->failure('文件校验失败！');
        }
        $tmpfile = tempnam(sys_get_temp_dir(), 'storeinstall');
        file_put_contents($tmpfile, $content);
        $plugin['tmpfile'] = $tmpfile;
        $session->set('plugin', $plugin);
        return $this->success('下载成功！', '', $plugin);
    }
}
