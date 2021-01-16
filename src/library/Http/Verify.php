<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Traits\ResponseTrait;
use App\Ebcms\Admin\Traits\RestfulTrait;
use Ebcms\App;
use Ebcms\RequestFilter;

class Verify
{
    use RestfulTrait;
    use ResponseTrait;

    public function get(
        App $app,
        RequestFilter $input
    ) {
        try {
            $salt = file_get_contents($app->getAppPath() . '/runtime/salt.tmp');
            return md5($salt . $input->get('salt'));
        } catch (\Throwable $th) {
            return '';
        }
    }
}
