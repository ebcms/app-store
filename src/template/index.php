{include common/header@ebcms/admin}
<div class="h1 my-4">应用商店</div>
<div class="row">
    <div class="col-md-8">
        <ul class="nav mb-4 p-1" style="background-color: #f5f5f5;">
            <li class="nav-item">
                <a class="nav-link active" href="{:$router->buildUrl('/ebcms/store/index')}">全部</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{:$router->buildUrl('/ebcms/store/index', ['type'=>'功能'])}">功能</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{:$router->buildUrl('/ebcms/store/index', ['type'=>'模板'])}">模板</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{:$router->buildUrl('/ebcms/store/index', ['type'=>'其他'])}">其他</a>
            </li>
        </ul>
        <style>
            .plugin-item:hover {
                background-color: #ffffbb;
            }
        </style>
        <ul class="list-unstyled">
            {foreach $items as $vo}
            <li class="media position-relative p-2 plugin-item">
                <img style="cursor:pointer;height:80px;width:80px;" class="img-thumbnail img-fluid mr-3 p-2" src="{$vo['logo']??''}">
                <div class="media-body">
                    <h5 class="mt-0 mb-1">{$vo['title']??$vo['name']}</h5>
                    <div class="text-muted">{$vo['description']??'暂无介绍'}</div>
                    <div>
                        <a class="text-decoration-none stretched-link" href="{:$router->buildUrl('/ebcms/store/detail',['plugin_name'=>$vo['name']])}">详情</a>
                    </div>
                </div>
            </li>
            {/foreach}
        </ul>
        <nav>
            <ul class="pagination">
                {foreach $pages as $v}
                {if $v=='...'}
                <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{$v}</a></li>
                {elseif isset($v['current'])}
                <li class="page-item active"><a class="page-link" href="javascript:void(0);">{$v.page}</a></li>
                {else}
                <li class="page-item"><a class="page-link" href="{:$router->buildUrl('/ebcms/store/index', array_merge($input->get(), ['page'=>$v['page']]))}">{$v.page}</a></li>
                {/if}
                {/foreach}
            </ul>
        </nav>
    </div>
    <div class="col-md-4">
        {include right@ebcms/store}
    </div>
</div>
{include common/header@ebcms/admin}