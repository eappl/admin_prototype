{tpl:tpl contentHeader/}
<script type="text/javascript">
  function SportsTypeAdd(){
    RaceStageId=$("#RaceStageId");
    RaceGroupId=$("#RaceGroupId");
    SportsType=$("#SportsTypeSelect");
    After = 0;
      location.href = '{tpl:$this.sign/}&ac=race.stage.group.sports.type.add&RaceGroupId=' + RaceGroupId.val() + '&RaceStageId=' + RaceStageId.val() + '&SportsTypeId=' + SportsType.val();

  }
</script>

<form action="{tpl:$this.sign/}&ac=race.stage.group.update" name="form" id="form" method="post">
<input type="hidden" name="RaceStageId" id="RaceStageId" value="{tpl:$oRaceStage.RaceStageId/}" />
  <input type="hidden" name="RaceGroupId" id="RaceGroupId" value="{tpl:$oRaceGroup.RaceGroupId/}" />
  <fieldset><legend>{tpl:$oRaceStage.RaceStageName/}-{tpl:$oRaceGroup.RaceGroupName/} 赛段详情配置 </legend>
<table width="99%" align="center" class="table table-bordered table-striped">

  <tr>
    <th align="center" class="rowtip">人数/价格对应</th>
    <th align="center" class="rowtip">是否接受个人报名</th>
    <th align="center" class="rowtip">是否接受团队报名</th>
    <th align="center" class="rowtip">起止时间</th>
  </tr>
  <tr>
    <th align="center" class="rowtip"><input name="RaceStageGroupInfo[PriceList]" type="text" class="span2" id="RaceStageGroupInfo[PriceList]" value="{tpl:$RaceStageGroupInfo.PriceList/}" size="50" /></th>
    <th align="center" class="rowtip"><input type="radio" name="RaceStageGroupInfo[SingleUser]" id="RaceStageGroupInfo[SingleUser]" value="1" {tpl:if($RaceStageGroupInfo.SingleUser=="1")}checked{/tpl:if}>接受
      <input type="radio" name="RaceStageGroupInfo[SingleUser]" id="RaceStageGroupInfo[SingleUser]"  value="0" {tpl:if($RaceStageGroupInfo.SingleUser=="0")}checked{/tpl:if}>不接受</th>
    <th align="center" class="rowtip"><input type="radio" name="RaceStageGroupInfo[TeamUser]" id="RaceStageGroupInfo[TeamUser]" value="1" {tpl:if($RaceStageGroupInfo.TeamUser=="1")}checked{/tpl:if}>接受
      <input type="radio" name="RaceStageGroupInfo[TeamUser]" id="RaceStageGroupInfo[TeamUser]" value="0" {tpl:if($RaceStageGroupInfo.TeamUser=="0")}checked{/tpl:if}>不接受</th>
    <th align="center" class="rowtip">
      <input type="text" name="RaceStageGroupInfo[StartTime]" value="{tpl:$RaceStageGroupInfo.StartTime/}" class="input-medium"
             onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
      ---
      <input type="text" name="RaceStageGroupInfo[EndTime]" value="{tpl:$RaceStageGroupInfo.EndTime/}" value="" class="input-medium"
             onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
    </th>
  </tr>
</table>
<table width="99%" align="center" class="table table-bordered table-striped">
  {tpl:if(count($RaceStageGroupInfo.comment.DetailList))}
  <tr>
  {tpl:loop $RaceStageGroupInfo.comment.DetailList $SportsTypeId $SportsTypeInfo}
  <tr>
  <th align="center" class="rowtip">{tpl:$SportsTypeInfo.SportsTypeName/}</th>
  </tr>
  {/tpl:loop}
  <tr>
    <th align="center" class="rowtip">继续添加
      <select name="SportsTypeSelect" id="SportsTypeSelect" size="1">
        {tpl:loop $SportTypeArr  $SportsType}
        <option value="{tpl:$SportsType.SportsTypeId/}" >{tpl:$SportsType.SportsTypeName/}</option>
        {/tpl:loop}
      </select>
      <button type="button" onclick="SportsTypeAdd()">添加</button>
    </th>
  </tr>
  {tpl:else}
  <tr>
    <th align="center" class="rowtip" colspan="4">尚未配置任何赛段计时点数据
      <select name="SportsTypeSelect" id="SportsTypeSelect" size="1">
      {tpl:loop $SportTypeArr  $SportsType}
      <option value="{tpl:$SportsType.SportsTypeId/}" >{tpl:$SportsType.SportsTypeName/}</option>
      {/tpl:loop}
      </select>
      <button type="button" onclick="SportsTypeAdd()">添加</button>
    </th>
  </tr>
  {/tpl:if}

</table>
</fieldset>
  <td><button type="submit" id="race_stage_group_submit">提交</button></td>
</form>
{tpl:tpl contentFooter/}
