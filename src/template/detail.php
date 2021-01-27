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
        <div class="media mb-4">
            <img style="cursor:pointer;height:100px;width:100px;" class="img-thumbnail img-fluid mr-3 p-2" src="{$plugin['logo']??''}">
            <div class="media-body">
                <?php $_installed = $server->getInstalled(); ?>
                {if !isset($_installed[$plugin['name']])}
                <a href="{:$router->buildUrl('/ebcms/store/item', ['plugin_name'=>$plugin['name']])}" class="btn btn-primary float-right">立即安装</a>
                {else}
                <?php $hasnew = version_compare($_installed[$plugin['name']], $plugin['version'], '<'); ?>
                {if $hasnew}
                <a href="{:$router->buildUrl('/ebcms/store/item', ['plugin_name'=>$plugin['name']])}" class="btn btn-warning float-right">可升级</a>
                {else}
                <a href="#" class="btn btn-secondary float-right disabled" role="button" aria-disabled="true">已安装最新版</a>
                {/if}
                {/if}
                <h5 class="mt-0 mb-1">{$plugin.title}</h5>
                <div class="text-muted">{$plugin['description']??'暂无介绍'}</div>
            </div>
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="content-tab" data-toggle="tab" href="#content" role="tab" aria-controls="content" aria-selected="true">介绍</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="changelog-tab" data-toggle="tab" href="#changelog" role="tab" aria-controls="changelog" aria-selected="false">更新日志</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="stats-tab" data-toggle="tab" href="#stats" role="tab" aria-controls="stats" aria-selected="false">统计</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade p-3 bg-light my-3 show active" id="content" role="tabpanel" aria-labelledby="content-tab"></div>
            <div class="tab-pane fade p-3 bg-light my-3" id="changelog" role="tabpanel" aria-labelledby="changelog-tab"></div>
            <div class="tab-pane fade p-3 bg-light my-3" id="stats" role="tabpanel" aria-labelledby="stats-tab">统计</div>
        </div>
    </div>
    <div class="col-md-4">
        {include right@ebcms/store}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/markdown-it@12.0.3/dist/markdown-it.min.js" integrity="sha256-w9HUyWlYpo2NY0GnFNkPqoxBdCNZNn1B3lgPQif2d2I=" crossorigin="anonymous"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/highlight.js/10.1.1/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@10.1.2/styles/vs.css">
<script>
    function base64Decode(input) {
        rv = window.atob(input);
        rv = escape(rv);
        rv = decodeURIComponent(rv);
        return rv;
    }
    var md = window.markdownit({
        highlight: function(str, lang) {
            if (lang && hljs.getLanguage(lang)) {
                try {
                    return '<pre class="hljs"><code>' +
                        hljs.highlight(lang, str, true).value +
                        '</code></pre>';
                } catch (__) {}
            }
            return '<pre class="hljs"><code>' + window.markdownit().utils.escapeHtml(str) + '</code></pre>';
        }
    });
    $("#content").html(md.render(base64Decode("{:base64_encode($plugin['content']??'__暂无介绍__')}")));
    $("#content a").attr("target", "_blank");
    $("#content table").addClass("table table-bordered my-3");
    $("#changelog").html(md.render(base64Decode("{:base64_encode($plugin['changelog']??'__暂无日志__')}")));
    $("#changelog a").attr("target", "_blank");
    $("#changelog table").addClass("table table-bordered my-3");
</script>
{include common/footer@ebcms/admin}