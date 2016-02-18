<div class="box">
            <div>
                <div class="red_box" style='display:none' id='error_div'>
                    <img src="images/error.gif" width="16" height="15" />请认真阅读并同意以下条款
                </div>

                <div class="gray_box">
                    <div class="in_box" style="height:314px; overflow-y:auto">
                        <p style="color:green;font-size:24px;margin:10px;">安装完成！</p>
                        <p style="color:red;margin-top:20px;"><img width="16" height="15" src="images/error.gif"> 警告： 为了增强安全性，您必须删除 'install' 文件夹和自述文件。</p>
                        <?php $v = include("../protected/version.php"); $version = $v."&n=".$_SERVER['SERVER_NAME'];?>
                        <iframe src="http://www.tinyrise.com/index.php?con=index&act=version&v=<?php echo $version ?>" width="100%" height="80" id="iframe"  frameborder="no" border="0"></iframe>
                        <?php
                            file_put_contents(APP_ROOT."install/install.lock",'');
                        ?>
                        <div style="text-align: center;margin-top:60px;">
                        <input class="button" type="button" onclick="window.location.href = '../index.php?con=admin';" value="进入后台"><input class="button" type="button" value="体验前台" onclick="window.location.href = '../';" />
                    </div>
                    </div>
                </div>
            </div>
    </div>
