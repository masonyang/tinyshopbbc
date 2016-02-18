    <script type='text/javascript' src='images/jquery.min.js'></script>
    <div class="box">
        <form action='index.php?action=installSql' method='post' target="install_iframe" onsubmit="return check_form();">
            <div>
                <div class="red_box" style='display:none' id='error_div'>
                    <img src="images/error.gif" width="16" height="15" /><span id="error_info"></span>
                </div>
                <div class="gray_box">
                    <div class="in_box">
                        
                            <table class="default">
                                <colgroup><col width="100px">
                                <col>
                                </colgroup><tbody><tr>
                                    <th>数据库地址</th><td><input class="gray" type="text" name="db_address" value="127.0.0.1:3306"> <label>MYSQL数据库的地址，本地默认：127.0.0.1:3306</label></td>
                                </tr>
                                <tr>
                                    <th>数据库名称</th><td><input class="gray" type="text" name="db_name"> <label class="fail" id="db_name_label" style="padding-left:24px;display:none">请填写正确的数据库名称</label></td>
                                </tr>
                                <tr>
                                    <th>账户</th><td><input class="gray" type="text" value="root" name="db_user">
                                <label class="fail"  style="display:none">账号不能为空</label></td>
                                </tr>
                                <tr>
                                    <th>密码</th><td><input class="gray" type="password" name="db_pwd"></td>
                                </tr>
                                <tr>
                                    <th>数据库表前缀</th>
                                    <td><input class="gray" type="text" value="tiny_" name="db_pre"> <label class="fail"  style="display:none">数据库表前缀不能为空!</label></td>
                                </tr>
                                <tr>
                                    <th></th><td><input class="button" value="检测连接" type="button" onclick="check_connect();"></td>
                                </tr>
                            </tbody></table>

                            <p id="connect_status" style="display:none">数据库连接正确</p>
                            <hr>

                            <table class="default">
                                <colgroup><col width="100px">
                                <col>
                                </colgroup><tbody><tr>
                                    <th>管理员账户</th>
                                    <td>
                                        <input class="gray" type="text" name="admin_user" value="admin"><label class="fail"  style="display:none">管理里员账号必需为4-20位有效字符</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>密码</th>
                                    <td>
                                        <input class="gray" type="password" name="admin_pwd"><label class="fail"  style="display:none">密码格式不正确，字符在6-20位之间</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>再次确认</th>
                                    <td>
                                        <input class="gray" type="password" name="admin_repwd"><label class="fail"  style="display:none">二次密码输入的不一致</label>
                                    </td>
                                </tr>
                            </tbody></table>


                            <div id="install_status" style="display:none">
                                <strong>安装进度</strong>
                                <span id="install_status_info">正在安装,请稍后...</span>
                                <div id="install_bar_bg"><span id="install_bar" style="width:0px;"></span></div>
                            </div>
                        
                        </div>
                </div>
            </div>
            <p style="text-align:right">
                <input class="button" type="button" onclick="window.location.href = 'index.php?step=2';" value="上一步"><input class="button" type="submit" value="下一步"  /></p></form>
    </div>
    <iframe name='install_iframe' style='width:0;height:0;display:none;' src='#'></iframe>
    <script type='text/javascript'>
        
    //检查表单信息
    function check_form()
    {
        $('label.error').hide();
        var checkObj   =
        {
            db_name   :/^.+$/i,
            db_user	  :/^.+$/,
            db_pre    :/\w+/,
            admin_user:/^.{4,20}$/i,
            admin_pwd :/^.{6,20}$/i
        };

        for(val in checkObj)
        {
            var matchResult = $.trim($('[name="'+val+'"]').val()).match(checkObj[val]);
            if(matchResult == null)
            {
                $('[name="'+val+'"]').focus().next("label").show();
                return false;
            }
            else $('[name="'+val+'"]').focus().next("label").hide();
        }

        if($('[name="admin_repwd"]').val() != $('[name="admin_pwd"]').val())
        {
            $('[name="admin_repwd"]').focus().next("label").show();
            return false;
        }
        else{
            $('[name="admin_repwd"]').focus().next("label").hide();
        }

        //$('#install_status').show();
        //$('.next').attr('disabled','disabled');
        return true;
    }

    //检查mysql链接
    function check_connect()
    {
        var sendData = {'db_address':'','db_user':'','db_pwd':''};
        for(val in sendData)
        {
            sendData[val] = $('[name="'+val+'"]').val();
        }

        $.get('index.php?action=checkConnect&'+Math.random(),sendData,function(content)
        {
            if(content == "success")
            {
                $('#connect_status').removeClass('connect_fail').addClass('connect_success').text('数据库连接正确').show();
            }
            else
            {
                $('#connect_status').removeClass('connect_success').addClass('connect_fail').text('数据库连接检测失败，请核对用户名及密码是否正确！').show();
            }
        });
    }
</script>