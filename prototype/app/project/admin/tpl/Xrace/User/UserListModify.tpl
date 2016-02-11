{tpl:tpl contentHeader/}
<div class="br_bottom"></div>
<form id="user_update_form" name="user_update_form" action="{tpl:$this.sign/}&ac=user.list.update" metdod="post">
<input type="hidden" name="user_id" value="{tpl:$User.user_id/}" />   
<table width="99%" align="center" class="table table-bordered table-striped">
<tr class="hover"><td>用户ID</td>
<td align="left">{tpl:$User.user_id/}</td>
</tr>
<tr class="hover">
    <td>用户昵称:</td>
    <td align="left"><input type="text" class="span4" name="nick_name"  id="nick_name" value="{tpl:$User.nick_name/}" size="50" /></td>
</tr>
<tr class="hover">
    <td>用户密码:</td>
    <td align="left"><input type="password" class="span4" name="pwd"  id="pwd" value="{tpl:$User.pwd/}" size="50" /></td>
</tr>
<tr class="hover"><td>性别:</td>
    <td align="left">	
        <select name="sex" size="1">
        {tpl:loop $SexArr $SexValue}
        <option value="{tpl:$SexValue/}" {tpl:if($SexValue==$User.sex)}selected="selected"{/tpl:if}>{tpl:$SexValue/}</option>
        {/tpl:loop}
        </select>
    </td>
</tr>
<tr class="hover"><td>验证状态:</td>
    <td align="left">	
        <select name="auth_state" size="1">
        {tpl:loop $AuthStateArr $AuthStateValue}
        <option value="{tpl:$AuthStateValue/}" {tpl:if($AuthStateValue==$User.auth_state)}selected="selected"{/tpl:if}>{tpl:$AuthStateValue/}</option>
        {/tpl:loop}
        </select>
    </td>
</tr>
<tr class="noborder"><td></td>
<td><button type="submit" id="user_update_submit">提交</button></td>
</tr>
</table>
</form>
<script type="text/javascript">
$('#user_update_submit').click(function(){
	var options = {
		dataType:'json',
		beforeSubmit:function(formData, jqForm, options) {},
		success:function(jsonResponse) {
			if (jsonResponse.errno) {
				var errors = [];
				errors[1] = '用户昵称不能为空，请修正后再次提交';
				errors[3] = '用户密码不能为空，请修正后再次提交';
				errors[9] = '入库失败，请修正后再次提交';
				divBox.alertBox(errors[jsonResponse.errno],function(){});
			} else {
				var message = '修改用户成功';
				divBox.confirmBox({content:message,ok:function(){windowParent.getRightHtml('{tpl:$this.sign/}');}});
			}
		}
	};
	$('#user_update_form').ajaxForm(options);
});
</script>
{tpl:tpl contentFooter/}