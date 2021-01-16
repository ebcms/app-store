<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Traits\DirTrait;
use Ebcms\App;
use Exception;
use Throwable;
use Ebcms\Session;
use ZipArchive;

use function Composer\Autoload\includeFile;

class Install extends Common
{
    use DirTrait;

    public function get(
        App $app,
        Session $session
    ) {
        if (!$plugin = $session->get('plugin')) {
            return $this->failure('操作错误，请重试~');
        }
        $plugin_dir = $app->getAppPath() . '/plugin/' . $plugin['name'];
        try {

            $lock_file = $app->getAppPath() . '/config/plugin/' . $plugin['name'] . '/install.lock';
            if (
                file_exists($lock_file)
            ) {
                $type = 'upgrade';
            } else {
                $type = 'install';
            }

            $this->delDir($plugin_dir);
            $this->unZip($plugin['tmpfile'], $plugin_dir);

            $json_file = $app->getAppPath() . '/plugin/' . $plugin['name'] . '/plugin.json';
            if (!file_exists($json_file)) {
                return $this->failure('安装文件无效！');
            }
            $json = (array)json_decode(file_get_contents($json_file), true);
            if (
                !isset($json['name']) ||
                $json['name'] != $plugin['name'] ||
                !isset($json['version']) ||
                $json['version'] != $plugin['version']
            ) {
                return $this->failure('安装文件无效！');
            }

            if (file_exists($plugin_dir . '/' . $type . '.php')) {
                includeFile($plugin_dir . '/' . $type . '.php');
            }

            if (file_exists($plugin['tmpfile'])) {
                unlink($plugin['tmpfile']);
            }
            $session->delete('plugin');

            if (!is_dir(dirname($lock_file))) {
                mkdir(dirname($lock_file), 0755, true);
            }
            if (file_exists(dirname($lock_file) . '/disabled.lock')) {
                unlink(dirname($lock_file) . '/disabled.lock');
            }
            file_put_contents($lock_file, date(DATE_ISO8601));

            return $this->success('安装成功!');
        } catch (Throwable $th) {
            $session->delete('plugin');
            try {
                $this->delDir($plugin_dir);
                foreach ($plugin['backup_dirs'] as $dir) {
                    if (file_exists($app->getAppPath() . $dir)) {
                        unlink($app->getAppPath() . $dir);
                    } elseif (is_dir($app->getAppPath() . $dir)) {
                        $this->delDir($app->getAppPath() . $dir);
                    }
                }
                $this->copyDir($plugin['backup_path'], $app->getAppPath());
            } catch (\Throwable $th2) {
                return $this->failure('(还原失败，请手动还原!)' . $th->getMessage());
            }
            return $this->failure($th->getMessage());
        }
    }

    private function unZip($file, $destination)
    {
        $zip = new ZipArchive();
        if ($zip->open($file) !== TRUE) {
            throw new Exception('Could not open archive');
        }
        $zip->extractTo($destination);
        $zip->close();
    }
}
