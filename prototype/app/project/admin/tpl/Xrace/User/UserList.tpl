{tpl:tpl contentHeader/}
<script type="text/javascript">
$(document).ready(function(){
	$('#add_app').click(function(){
		addAppBox = divBox.showBox('{tpl:$this.sign/}&ac=user.list.add', {title:'添加用户',width:500,height:200});
	});
});

function RaceTypeDelete(p_id, p_name){
	deleteAppBox = divBox.confirmBox({content:'是否删除 ' + p_name + '?',ok:function(){location.href = '{tpl:$this.sign/}&ac=user.list.delete&RaceTypeId=';}});
}

function RaceTypeModify(mid){
	modifyRaceTypeBox = divBox.showBox('{tpl:$this.sign/}&ac=user.list.modify&RaceTypeId=' + mid, {title:'修改用户',width:500,height:200});
}

</script>

<fieldset><legend>操作</legend>
[ <a href="javascript:;" id="add_app">添加用户</a> ]
</fieldset>

<fieldset><legend>用户列表 </legend>
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
      <td align="right" colspan="">{tpl:$page_content/}</td>
  </tr>
</table>
</fieldset>
{tpl:tpl contentFooter/}
