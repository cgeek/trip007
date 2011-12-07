function uploadModal(b, a, e, d) {
    var c = '<iframe id="upload-iframe" style="display:none;" src="about:blank" name="upload-iframe"></iframe><form id="upload-form" action="/upload/img" method="post" enctype="multipart/form-data"><h4>插入一张图片</h4><p class="nav"><a href="#" id="upload-local" class="radius-3 selected">从本地上传</a><a href="#" id="upload-remote" class="radius-3">从远程地址获取</a></p><p class="file-upload" for="upload-local"><input type="file" name="img" id="filename-input" /><input type="text" id="filename-text" disabled class="text" /><button id="filename-button" disabled>选择文件</button><br/><cite class="desc">图片文件体积最大不超过4M</cite></p><p class="file-upload-remote" for="upload-remote"><input type="text" id="fileurl-text" name="url" class="text" /><br /><cite class="desc">输入一个图片地址上传</cite></p><p class="submit"><button type="submit">上传</button> <button class="cancel">取消</button></p></form>';
    $(c).modal({
        onShow: function(g) {
            var f = false,
            h = false;
            $("#filename-input").click(function() {
                if (g.uploaded) {
                    return false
                }
            });
            $("#filename-input").change(function() {
                $("#filename-text").val($(this).val())
            });
            $("#upload-local").click(function() {
                if (!g.uploaded) {
                    $(this).addClass("selected");
                    $("#upload-remote").removeClass("selected");
                    $('p[for="upload-local"]').show();
                    $('p[for="upload-remote"]').hide();
                    h = false
                }
                return false
            });
            $("#upload-remote").click(function() {
                if (!g.uploaded) {
                    $(this).addClass("selected");
                    $("#upload-local").removeClass("selected");
                    $('p[for="upload-remote"]').show();
                    $('p[for="upload-local"]').hide();
                    h = true
                }
                return false
            });
            uploadCall = {
                set: function(i) {
                    var j = d("{{#" + i + "|请输入图片描述}}");
                    e(j + i.length + 4, 7);
                    g.uploaded = false;
                    g.trigger("close");
                    b.trigger("change")
                },
                error: function(i) {
                    g.uploaded = false;
                    $("#filename-text").removeClass("upload-loading");
                    $("#filename-input").removeAttr("disabled");
                    $(".file-upload cite.desc").stop().css({
                        backgroundColor: "#ffffff",
                        color: "#aa0000"
                    }).html(i).animate({
                        backgroundColor: "#aa0000",
                        color: "#ffffff"
                    },
                    "fast")
                }
            };
            $(".cancel", g).click(function() {
                g.trigger("close");
                return false
            });
            $("#upload-form").submit(function(k) {
                if (h) {
                    alert('hello , h is true');
                    $("#fileurl-text").addClass("upload-loading").attr("disabled", "disabled");
                    g.uploaded = true;
                    $.post("/upload/img", {
                        url: $("#fileurl-text").val()
                    },
                    function(n) {
                        alert('update callback');
                        if (n.success) {
                            var p = d("{{#" + n.id + "|请输入图片描述}}");
                            e(p + 4 + n.id.length, 7);
                            g.trigger("close");
                            b.trigger("change")
                        } else {
                            var m = $("#fileurl-text").removeClass("upload-loading").removeAttr("disabled");
                            $(".file-upload-remote cite.desc").stop().css({
                                backgroundColor: "#ffffff",
                                color: "#aa0000"
                            }).html(n.message).animate({
                                backgroundColor: "#aa0000",
                                color: "#ffffff"
                            },
                            "fast");
                            m.select()
                        }
                        g.uploaded = false
                    },
                    "json")
                } else {
                    alert('h is false');
                    var l = $("#upload-iframe")[0],
                    j = l.contentWindow ? l.contentWindow.document: l.contentDocument ? l.contentDocument: l.document,
                    i = $('<form id="upload-form" action="/upload/img" method="post" enctype="multipart/form-data"></form>');
                    i.append($("#filename-input"));
                    $("body", j).html("").append(i);
                    $('<input type="file" name="img" id="filename-input" disabled />').insertBefore("#filename-text").change(function() {
                        $("#filename-text").val($(this).val())
                    });
                    $("#filename-text").addClass("upload-loading");
                    g.uploaded = true;
                    i.submit()
                }
                return false;
            })
        }
    })
};
