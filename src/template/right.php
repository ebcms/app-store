<div class="h4 mb-3">可更新</div>
<div class="mb-3">
    <?php $res = $server->query('/find-upgrade'); ?>
    {if $res['status']==200}
    {if $res['data']}
    <ul class="list-unstyled list-inline">
        {foreach $res['data'] as $vo}
        <li class="list-inline-item text-center">
            <a href="{:$router->buildUrl('/ebcms/store/detail',['plugin_name'=>$vo['name']])}">
                <img src="{$vo['logo']??''}" alt="..." class="img-thumbnail p-2" style="width:80px;height:80px;">
                <div class="mt-2 mb-3">{$vo.title}</div>
            </a>
        </li>
        {/foreach}
    </ul>
    {else}
    <i>暂无</i>
    {/if}
    {else}
    <div class="alert alert-warning">{$res['message']??'接口出错，请稍后再试~'}</div>
    {/if}
</div>

{cache 10}
<div class="h4 mb-3">推荐</div>
<div class="mb-3">
    <?php $res = $server->query('/good'); ?>
    {if $res['status']==200}
    <ul class="list-unstyled list-inline">
        {foreach $res['data'] as $vo}
        <li class="list-inline-item text-center">
            <a href="{:$router->buildUrl('/ebcms/store/detail',['plugin_name'=>$vo['name']])}">
                <img src="{$vo['logo']??''}" alt="..." class="img-thumbnail p-2" style="width:80px;height:80px;">
                <div class="mt-2 mb-3">{$vo.title}</div>
            </a>
        </li>
        {/foreach}
    </ul>
    {else}
    <div class="alert alert-warning">{$res['message']??'接口出错，请稍后再试~'}</div>
    {/if}
</div>
{/cache}

{cache 10}
<div class="h4 mb-3">近期上架</div>
<div class="mb-3">
    <?php $res = $server->query('/newest'); ?>
    {if $res['status']==200}
    <ul class="list-unstyled list-inline">
        {foreach $res['data'] as $vo}
        <li class="list-inline-item text-center">
            <a href="{:$router->buildUrl('/ebcms/store/detail',['plugin_name'=>$vo['name']])}">
                <img src="{$vo['logo']??''}" alt="..." class="img-thumbnail p-2" style="width:80px;height:80px;">
                <div class="mt-2 mb-3">{$vo.title}</div>
            </a>
        </li>
        {/foreach}
    </ul>
    {else}
    <div class="alert alert-warning">{$res['message']??'接口出错，请稍后再试~'}</div>
    {/if}
</div>
{/cache}