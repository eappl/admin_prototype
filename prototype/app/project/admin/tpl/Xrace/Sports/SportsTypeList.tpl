{tpl:tpl contentHeader/}
<script type="text/javascript">
$(document).ready(function(){
	$('#add_app').click(function(){
		addAppBox = divBox.showBox('{tpl:$this.sign/}&ac=sports.type.add', {title:'添加运动类型',width:800,height:600});
	});
});

function sportsTypeDelete(p_id, p_name){
	deleteAppBox = divBox.confirmBox({content:'是否删除 ' + p_name + '?',ok:function(){location.href = '{tpl:$this.sign/}&ac=sports.type.delete&sportsTypeId=' + p_id;}});
}

function sportsTypeModify(mid){
	modifySportsTypeBox = divBox.showBox('{tpl:$this.sign/}&ac=sports.type.modify&sportsTypeId=' + mid, {title:'修改运动类型',width:800,height:600});
}

function sportsTypeParamsModify(mid){
  modifySportsTypeBox = divBox.showBox('{tpl:$this.sign/}&ac=sports.type.params.modify&sportsTypeId=' + mid, {title:'修改运动类型',width:800,height:600});
}

</script>

<fieldset><legend>操作</legend>
[ <a href="javascript:;" id="add_app">添加运动类型</a> ]
</fieldset>

<fieldset><legend>运动类型列表 </legend>
<table width="99%" align="center" class="table table-bordered table-striped">
  <tr>
    <th align="center" class="rowtip">运动类型ID</th>
    <th align="center" class="rowtip">运动类型名称</th>
    <th align="center" class="rowtip">操作</th>
  </tr>

{tpl:loop $SportTypeArr $oSportsType}
  <tr class="hover">
    <td align="center">{tpl:$oSportsType.SportsTypeId/}</td>
    <td align="center">{tpl:$oSportsType.SportsTypeName/}</td>
    <td align="center"><a  href="javascript:;" onclick="sportsTypeDelete('{tpl:$oSportsType.SportsTypeId/}','{tpl:$oSportsType.SportsTypeName/}')">删除</a> |  <a href="javascript:;" onclick="sportsTypeModify('{tpl:$oSportsType.SportsTypeId/}');">修改</a></td>
  </tr>
{/tpl:loop}
</table>
</fieldset>
{tpl:tpl contentFooter/}
