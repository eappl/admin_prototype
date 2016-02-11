{tpl:tpl contentHeader/}
<div class="br_bottom"></div>
<form id="producttype_add_form" name="producttype_add_form" action="{tpl:$this.sign/}&ac=insert" metdod="post">
		<fieldset><legend>添加产品类型</legend>

		<table widtd="99%" align="center" class="table table-bordered table-striped" widtd="99%">

		<tr class="hover">
			<td>产品类型ID</td>
			<td align="left"><input name="ProductTypeId" type="text" class="span4" id="ProductTypeId" value="" size="50" /></td>
		</tr>
		
		<tr class="hover">
			<td>产品类型名称</td>
			<td align="left"><input name="name" type="text" class="span4" id="name" value="" size="50" /></td>
		</tr>

		<tr class="hover">
			<td>选择游戏</td>
			<td align="left">
			<select name = "AppId" id = "AppId">
			{tpl:loop $AppList $key $app}
			<option value = {tpl:$key/} {tpl:if ($key==$AppId)}selected{/tpl:if}>{tpl:$app.name/}</option>
			{/tpl:loop}
			</select>
</td>
		</tr>

		<tr class="noborder"><td></td>
		<td><button type="submit" id="producttype_add_submit">提交</button></td>
		</tr>
	</table>
	</fieldset>
	</form>
	 
</dl>
<script type="text/javascript">
document.getElementById('name').focus();
$('#producttype_add_submit').click(function(){
	var options = {
		dataType:'json',
		beforeSubmit:function(formData, jqForm, options) {},
		success:function(jsonResponse) {
			if (jsonResponse.errno) {
				var errors = [];
				errors[1] = '失败，必须选定一个游戏';
				errors[2] = '失败，必须输入产品类型名称';
				errors[3] = '失败，必须输入产品类型ID';
				errors[9] = '失败，请修正后再次提交';
				divBox.alertBox(errors[jsonResponse.errno],function(){});
			} else {
				var message = '添加产品类型成功';
				divBox.confirmBox({content:message,ok:function(){windowParent.getRightHtml('{tpl:$this.sign/}'+ '&AppId=' + jsonResponse.AppId);}});

			}
		}
	};
	$('#producttype_add_form').ajaxForm(options);
});

</script>
{tpl:tpl contentFooter/}
