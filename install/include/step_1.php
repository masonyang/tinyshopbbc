	<div class="box">
			<div>
				<div class="red_box" style='display:none' id='error_div'>
					<img src="images/error.gif" width="16" height="15" />请认真阅读并同意以下条款
				</div>

				<div class="gray_box">
					<div class="in_box" style="height:314px; overflow-y:auto">
						<?php
						//动态获取license协议
						$licenseFile = dirname(__FILE__).'/../../license.txt';
						if(file_exists($licenseFile))
						{
							foreach(file($licenseFile) as $key => $val)
							{
								echo $val.'<br />';
							}
						}
						else
						{
							echo "";
						}
						?>
					</div>
				</div>

				<p class="agree"><label><input type="checkbox" id='agree' /> 我同意上述条款和条件</label></p>
			</div>
			<p style="text-align:right"><input class="button" type="button" value="下一步" onclick="check_license();" /></p>
	</div>
	<script type='text/javascript'>
	//检查协议阅读状态
	function check_license()
	{
		var is_agree = document.getElementById('agree').checked;
		if(is_agree == true)
		{
			window.location.href='index.php?step=2';
		}
		else
		{
			document.getElementById('error_div').style.display = '';
		}
	}
</script>