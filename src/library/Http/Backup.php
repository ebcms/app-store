<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Traits\DirTrait;
use Ebcms\App;
use Ebcms\Session;

class Backup extends Common
{
    use DirTrait;

    public function get(
        App $app,
        Session $session
    ) {
        $plugin = $session->get('plugin');
        $plugin['backup_path'] = $app->getAppPath() . '/backup/' . 'system_' . date('YmdHis');
        $plugin['backup_dirs'] = [
            'config',
            'hook',
            'plugin',
            'vendor',
            'composer.json',
            'composer.lock',
        ];
        $this->backup($plugin['backup_dirs'], $app->getAppPath(), $plugin['backup_path']);
        $session->set('plugin', $plugin);
        return $this->success('备份成功！', '', $plugin);
    }

    private function backup(array $items, string $path, string $target)
    {
        foreach ($items as $item) {
            if (is_file($path . '/' . $item)) {
                if (!is_dir(dirname($target . '/' . $item))) {
                    mkdir(dirname($target . '/' . $item), 0755, true);
                }
                copy($path . '/' . $item, $target . '/' . $item);
            } else {
                $this->copyDir($path . '/' . $item, $target . '/' . $item);
            }
        }
    }
}
