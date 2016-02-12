{tpl:tpl contentHeader/}
<div class="br_bottom"></div>


    <table width="99%" align="center" class="table table-bordered table-striped" widtd="99%">
        <tr class="hover">
            <th align="center" class="rowtip" rowspan="7" colspan="2"><img src="{tpl:$UserInfo.thumb/}" width='80' height='60'/></th>
        </tr>
        <tr class="hover">
            <th align="center" class="rowtip">用户ID</th>
            <td align="left">{tpl:$UserInfo.user_id/}</td>
        </tr>
        <tr class="hover">
            <th align="center" class="rowtip">用户姓名</th>
            <td align="left">{tpl:$UserInfo.name/}</td>
        </tr>
        <tr class="hover">
            <th align="center" class="rowtip">用户性别</th>
            <td align="left">{tpl:$UserInfo.sex/}</td>
        </tr>
        <tr class="hover">
            <th align="center" class="rowtip">联系电话</th>
            <td align="left">{tpl:$UserInfo.phone/}</td>
        </tr>
        <tr class="hover">
            <th align="center" class="rowtip">实名认证状态</th>
            <td align="left">{tpl:$UserInfo.auth_state/}</td>
        </tr>
        <tr class="hover">
            <th align="center" class="rowtip">微信openId</th>
            <td align="left">{tpl:$UserInfo.wx_open_id/}</td>
        </tr>
        <tr class="hover">
            <th align="center" class="rowtip">联系电话</th>
            <td align="left">{tpl:$UserInfo.phone/}</td>
        </tr>
    </table>

{tpl:tpl contentFooter/}