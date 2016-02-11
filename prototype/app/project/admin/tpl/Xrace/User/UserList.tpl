{tpl:tpl contentHeader/}
<script type="text/javascript">
$(document).ready(function(){
	$('#add_app').click(function(){
		addAppBox = divBox.showBox('{tpl:$this.sign/}&ac=user.list.add', {title:'添加用户',width:500,height:200});
	});
});

function UserDelete(){
	deleteAppBox = divBox.confirmBox({content:'是否删除 ' + p_name + '?',ok:function(){location.href = '{tpl:$this.sign/}&ac=user.list.delete';}});
}

function UserModify(){
	modifyRaceTypeBox = divBox.showBox('{tpl:$this.sign/}&ac=user.list.modify' + mid, {title:'修改用户',width:500,height:200});
}

</script>

<fieldset><legend>操作</legend>
[ <a href="javascript:;" id="add_app">添加用户</a> ]
</fieldset>
<form action="{tpl:$this.sign/}" name="form" id="form" method="post">
  用户ID: <input name="UserId"  type="text" size="1" value="{tpl:$UserId/}"/>
  性别: <select name="Sex" size="1">
        <option value="">全部</option>
        {tpl:loop $SexArr $SexValue}
        <option value="{tpl:$SexValue/}" {tpl:if($SexValue==$Sex)}selected="selected"{/tpl:if}>{tpl:$SexValue/}</option>
        {/tpl:loop}
       </select>
  认证状态: <select name="AuthState" size="1">
          <option value="">全部</option>
        {tpl:loop $AuthStateArr $AuthStateValue}
        <option value="{tpl:$AuthStateValue/}" {tpl:if($AuthStateValue==$AuthState)}selected="selected"{/tpl:if}>{tpl:$AuthStateValue/}</option>
        {/tpl:loop}
        </select>
  <input type="submit" name="Submit" value="查询" />
</form>

<fieldset><legend>用户列表</legend>
<table width="99%" align="center" class="table table-bordered table-striped">
  <tr>
    <th align="center" class="rowtip">用户分类ID</th>
    <th align="center" class="rowtip">性别</th>
    <th align="center" class="rowtip">认证状态</th>
  </tr>

{tpl:loop $userListArr $userList}
  <tr class="hover">
    <td align="center">{tpl:$userList.user_id/}</td>
    <td align="center">{tpl:$userList.sex/}</td>
    <td align="center">{tpl:$userList.auth_state/}</td> 
  </tr>
{/tpl:loop}
  <tr class="hover">
      <td colspan="3">{tpl:$page_content/}</td>
  </tr>
</table>
</fieldset>
{tpl:tpl contentFooter/}
