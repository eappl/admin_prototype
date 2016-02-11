{tpl:tpl contentHeader/}
<div class="br_bottom"></div>
<form id="user_add_form" name="user_add_form" action="{tpl:$this.sign/}&ac=user.list.insert" metdod="post">
<table width="99%" align="center" class="table table-bordered table-striped">
<tr class="hover">
    <td>用户ID:</td>
    <td align="left"><input type="text" class="span4" name="user_id"  id="user_id" value="" size="50" /></td>
</tr>
<tr class="hover">
    <td>用户昵称:</td>
    <td align="left"><input type="text" class="span4" name="nick_name"  id="nick_name" value="" size="50" /></td>
</tr>
<tr class="hover">
    <td>用户密码:</td>
    <td align="left"><input type="password" class="span4" name="pwd"  id="pwd" value="" size="50" /></td>
</tr>
<tr class="hover">
    <td>确认密码:</td>
    <td align="left"><input type="password" class="span4" name="confirm_pwd"  id="confirm_pwd" value="" size="50" /></td>
</tr>
<tr class="hover"><td>性别:</td>
    <td align="left">	
        <select name="sex" size="1">
        {tpl:loop $SexArr $SexValue}
        <option value="{tpl:$SexValue/}">{tpl:$SexValue/}</option>
        {/tpl:loop}
        </select>
    </td>
</tr>
<tr class="hover"><td>验证状态:</td>
    <td align="left">	
        <select name="auth_state" size="1">
        {tpl:loop $AuthStateArr $AuthStateValue}
        <option value="{tpl:$AuthStateValue/}">{tpl:$AuthStateValue/}</option>
        {/tpl:loop}
        </select>
    </td>
</tr>
<tr class="noborder"><td></td>
<td><button type="submit" id="user_add_submit">提交</button></td>
</tr>
</table>
</form>
<script type="text/javascript">
$('#user_add_submit').click(function(){
	var options = {
		dataType:'json',
		beforeSubmit:function(formData, jqForm, options) {},
		success:function(jsonResponse) {
			if (jsonResponse.errno) {
				var errors = [];
				errors[1] = '用户ID不能为空，请修正后再次提交';
				errors[3] = '用户昵称不能为空，请修正后再次提交';
                                errors[5] = '用户密码不能为空，请修正后再次提交';
                                errors[7] = '两次输入密码不一致，请修正后再次提交';
				errors[9] = '入库失败，请修正后再次提交';
				divBox.alertBox(errors[jsonResponse.errno],function(){});
			} else {
				var message = '添加用户成功';
				divBox.confirmBox({content:message,ok:function(){windowParent.getRightHtml('{tpl:$this.sign/}');}});
			}
		}
	};
	$('#user_add_form').ajaxForm(options);
});
</script>
{tpl:tpl contentFooter/}