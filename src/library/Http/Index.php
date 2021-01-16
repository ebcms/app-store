<?php

declare(strict_types=1);

namespace App\Ebcms\Store\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Store\Model\Server;
use Ebcms\Pagination;
use Ebcms\RequestFilter;
use Ebcms\Template;

class Index extends Common
{
    public function get(
        RequestFilter $input,
        Pagination $pagination,
        Template $template,
        Server $server
    ) {
        $data = [];
        $res = $server->query('/search', [
            'page' => $input->get('page'),
            'q' => $input->get('q'),
        ]);
        if ($res['status'] != 200) {
            return $this->failure($res['message']);
        }
        $data['items'] = $res['data']['items'];
        $data['page_size'] = $res['data']['page_size'];
        $data['page'] = $res['data']['page'];
        $data['total'] = $res['data']['total'];
        $data['pages'] = $pagination->render($data['page'], $data['total'], $data['page_size']);
        $data['server'] = $server;

        return $template->renderFromFile('index@ebcms/store', $data);
    }
}
