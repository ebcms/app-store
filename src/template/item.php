{include common/header@ebcms/admin}
<div class="media my-4">
    <img style="cursor:pointer;height:100px;width:100px;" class="img-thumbnail img-fluid mr-3 p-2" src="{$plugin.icon}">
    <div class="media-body">
        <h5 class="mt-0 mb-1">{$plugin.title}</h5>
        <div class="text-muted">{$plugin['description']??'暂无介绍'}</div>
    </div>
</div>
<script>
    var EBCMS = {};
    $(function() {
        EBCMS.state = 0;
        EBCMS.ticket_code = '';
        EBCMS.stop = function(message) {
            EBCMS.console(message);
            $("#progress_main .progress-bar").addClass('bg-danger').removeClass("progress-bar-animated").html(message);
            EBCMS.state = 0;
            $("#handler").removeClass('btn-warning').addClass('btn-primary').html("一键{$type=='install'?'安装':'升级'}");
        };
        EBCMS.handler = function() {
            if (EBCMS.state) {
                EBCMS.state = 0;
            } else {
                if (confirm('此操作有风险，请先做系统备份，确认继续吗？')) {
                    EBCMS.state = 1;
                    $("#progress_main").removeClass("d-none");
                    $("#handler").removeClass('btn-primary').addClass('btn-warning').html('一键停止');
                    $("#progress_main .progress-bar").removeClass('bg-danger').addClass("progress-bar-animated");
                    EBCMS.ticket();
                }
            }
        }
        EBCMS.ticket = function() {
            setTimeout(function() {
                EBCMS.process('10%', "云端认证中...");
                $.ajax({
                    type: "GET",
                    url: "{$router->buildUrl('/ebcms/store/ticket')}",
                    data: {
                        plugin_name: "{$plugin.name}",
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.code) {
                            if (!EBCMS.state) {
                                EBCMS.stop('已停止(通过认证)');
                                return;
                            }
                            EBCMS.process('20%', "通过认证~");
                            EBCMS.ticket_code = response.data.ticket;
                            setTimeout(function() {
                                EBCMS.source();
                            }, 200);
                        } else {
                            EBCMS.stop("未通过认证：" + response.message);
                        }
                    },
                    error: function(context) {
                        EBCMS.stop("云端认证接口错误：" + context.statusText);
                    }
                });
            }, 1000);
        };
        EBCMS.source = function() {
            EBCMS.process('30%', "资源检测中...");
            setTimeout(function() {
                $.ajax({
                    type: "GET",
                    url: "{$router->buildUrl('/ebcms/store/source')}",
                    data: {
                        plugin_name: "{$plugin.name}",
                        ticket: EBCMS.ticket_code,
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.code) {
                            if (!EBCMS.state) {
                                EBCMS.stop('已停止(检测完毕)');
                                return;
                            }
                            EBCMS.process('40%', "存在版本：" + response.data.version);
                            setTimeout(function() {
                                EBCMS.download();
                            }, 200);
                        } else {
                            EBCMS.stop(response.message);
                        }
                    },
                    error: function(context) {
                        EBCMS.stop("资源检测接口错误：" + context.statusText);
                    }
                });
            }, 200);
        };
        EBCMS.download = function() {
            EBCMS.process('50%', "开始下载...");
            setTimeout(function() {
                $.ajax({
                    type: "GET",
                    url: "{$router->buildUrl('/ebcms/store/download')}",
                    dataType: "json",
                    success: function(response) {
                        if (response.code) {
                            if (!EBCMS.state) {
                                EBCMS.stop('已停止(下载完毕)');
                                return;
                            }
                            EBCMS.process('60%', "下载完毕");
                            setTimeout(function() {
                                EBCMS.backup();
                            }, 200);
                        } else {
                            EBCMS.stop("下载失败：" + response.message);
                        }
                    },
                    error: function(context) {
                        EBCMS.stop("下载接口错误：" + context.statusText);
                    }
                });
            }, 200);
        };
        EBCMS.backup = function() {
            EBCMS.process('70%', "系统备份中...");
            setTimeout(function() {
                $.ajax({
                    type: "GET",
                    url: "{$router->buildUrl('/ebcms/store/backup')}",
                    dataType: "json",
                    success: function(response) {
                        if (response.code) {
                            if (!EBCMS.state) {
                                EBCMS.stop('已停止(备份成功)');
                                return;
                            }
                            EBCMS.process('80%', "备份完毕~");
                            setTimeout(function() {
                                EBCMS.install();
                            }, 200);
                        } else {
                            EBCMS.stop("备份失败：" + response.message);
                        }
                    },
                    error: function(context) {
                        EBCMS.stop("备份接口错误：" + context.statusText);
                    }
                });
            }, 200);
        };
        EBCMS.install = function() {
            EBCMS.process('90%', "安装中...");
            setTimeout(function() {
                $.ajax({
                    type: "GET",
                    url: "{$router->buildUrl('/ebcms/store/install')}",
                    dataType: "json",
                    success: function(response) {
                        if (response.code) {
                            if ("{$type}" == 'upgrade') {
                                EBCMS.process('100%', "升级成功，即将进行下一个版本升级！");
                                setTimeout(function() {
                                    EBCMS.process('0%', "<hr>");
                                    EBCMS.ticket();
                                }, 1000);
                            } else {
                                EBCMS.process('100%', "恭喜，安装成功~");
                                EBCMS.state = 0;
                                $("#progress_main .progress-bar").removeClass("progress-bar-animated");
                                $("#handler").removeClass('btn-warning').addClass('btn-primary').html("安装成功").attr('disabled', true);
                            }
                        } else {
                            EBCMS.stop("安装失败：" + response.message);
                        }
                    },
                    error: function(context) {
                        EBCMS.stop("安装接口错误：" + context.statusText);
                    }
                });
            }, 200);
        };
        EBCMS.console = function(message) {
            $(".console").append("<div>" + message + "</div>");
            $(".console").scrollTop(99999999);
        }
        EBCMS.process = function(width, tips) {
            EBCMS.console(tips);
            $("#progress_main .progress-bar").html(tips).width(width);
        }
    });
</script>
<div class="my-4">
    {if $type == 'install'}
    <button class="btn btn-primary" onclick="EBCMS.handler();" id="handler">一键安装</button>
    {else}
    <button class="btn btn-warning" onclick="EBCMS.handler();" id="handler">一键升级</button>
    {/if}
</div>
<div id="progress_main" class="d-none my-4">
    <div class="version"></div>
    <div class="progress" style="height: 25px;">
        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
    </div>
    <style>
        .console {
            background-color: #000;
            height: 200px;
            width: 100%;
            overflow-y: auto;
        }
    </style>
    <div class="console mt-3 p-2 text-white">
    </div>
</div>
{include common/footer@ebcms/admin}