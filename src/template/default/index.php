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

        <div>
            {foreach $items as $vo}
            <div class="d-flex mb-2 p-1 plugin-item position-relative">
                <div class="me-2">
                    <img style="cursor:pointer;height:80px;width:80px;" class="img-thumbnail img-fluid mr-3 p-2" src="{$vo['logo']??''}">
                </div>
                <div class="flex-fill">
                    <div class="mt-0 mb-1"><strong>{$vo['title']??$name}</strong></div>
                    <div class="text-muted">{$vo['description']??'暂无介绍'}</div>
                    <div>
                        <a class="text-decoration-none stretched-link" href="{:$router->buildUrl('/ebcms/store/detail',['plugin_name'=>$vo['name']])}">详情</a>
                    </div>
                </div>
            </div>
            {/foreach}
        </div>
        <nav>
            <ul class="pagination">
                {foreach $pages as $v}
                {if $v=='...'}
                <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{$v}</a></li>
                {elseif isset($v['current'])}
                <li class="page-item active"><a class="page-link" href="javascript:void(0);">{$v.page}</a></li>
                {else}
                <li class="page-item"><a class="page-link" href="{:$router->buildUrl('/ebcms/store/index', array_merge($request->get(), ['page'=>$v['page']]))}">{$v.page}</a></li>
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