{tpl:tpl contentHeader/}
<script type="text/javascript">

</script>

<fieldset><legend>操作</legend>
</fieldset>
<form action="{tpl:$this.sign/}" name="form" id="form" method="post">
    姓名<input type="text" name="Name" value="{tpl:$params.Name/}" />
    昵称<input type="text" name="NickName" value="{tpl:$params.NickName/}" />
    性别<select name="Sex" size="1">
        <option value="">全部</option>
        {tpl:loop $SexList $SexSymble $SexName}
        <option value="{tpl:$SexSymble/}" {tpl:if($params.Sex==$SexSymble)}selected="selected"{/tpl:if}>{tpl:$SexName/}</option>
        {/tpl:loop}
    </select>

    <input type="submit" name="Submit" value="查询" />{tpl:$export_var/}
</form>
<fieldset><legend>用户列表</legend>
<table width="99%" align="center" class="table table-bordered table-striped">
      <tr>
        <th align="center" class="rowtip">用户ID</th>
          <th align="center" class="rowtip">用户昵称</th>
        <th align="center" class="rowtip">微信openId</th>
        <th align="center" class="rowtip">联系电话</th>
        <th align="center" class="rowtip">性别</th>
        <th align="center" class="rowtip">操作</th>
      </tr>
    {tpl:loop $UserList.UserList $oUserInfo}
      <tr class="hover">
        <td align="center">{tpl:$oUserInfo.user_id/}</td>
          <td align="center">{tpl:$oUserInfo.nick_name/}</td>
          <td align="center">{tpl:$oUserInfo.wx_open_id/}</td>
        <td align="center">{tpl:$oUserInfo.phone/}</td>
        <td align="center">{tpl:$oUserInfo.sex/}</td>
          <td align="center"><a  href="javascript:;" onclick="sportsTypeDelete('{tpl:$oSportsType.SportsTypeId/}','{tpl:$oSportsType.SportsTypeName/}')">删除</a> |  <a href="javascript:;" onclick="sportsTypeModify('{tpl:$oSportsType.SportsTypeId/}');">修改</a></td>
      </tr>
    {/tpl:loop}
    <tr><th colspan="10" align="center" class="rowtip">{tpl:$page_content/}</th></tr>

</table>
</fieldset>
{tpl:tpl contentFooter/}
