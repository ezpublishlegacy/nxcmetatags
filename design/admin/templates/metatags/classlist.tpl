<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{$group.name|wash|classgroup_icon( 'normal', $group.name|wash )}&nbsp;{'%group_name Metatags'|i18n( 'design/admin/metatags/classlist',, hash( '%group_name', $group.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<p>Check the classes that should have metatags.</p>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<form action={'metatags/classlist'|ezurl} method="post" name="ClassList">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title"><a href={'/metatags/grouplist'|ezurl}><img src={'back-button-16x16.gif'|ezimage} alt="{'Back to class groups.'|i18n( 'design/admin/class/classlist' )}" title="{'Back to class groups.'|i18n( 'design/admin/class/classlist' )}" /></a>&nbsp;{'Classes inside <%group_name> [%class_count]'|i18n( 'design/admin/class/classlist',, hash( '%group_name', $group.name, '%class_count', $class_count ) )|wash}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$class_count}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/class/classlist' )}" title="{'Invert selection.'|i18n( 'design/admin/class/classlist' )}" onclick="ezjs_toggleCheckboxes( document.ClassList, 'DeleteIDArray[]' ); return false;" /></th>
    <th>{'Name'|i18n('design/admin/class/classlist')}</th>
    <th class="tight">{'ID'|i18n('design/admin/class/classlist')}</th>
    <th>{'Identifier'|i18n('design/admin/class/classlist')}</th>
    <th>{'Modifier'|i18n('design/admin/class/classlist')}</th>
    <th>{'Modified'|i18n('design/admin/class/classlist')}</th>
    <th>{'Objects'|i18n('design/admin/class/classlist')}</th>
</tr>

{section var=Classes loop=$groupclasses sequence=array( bglight, bgdark )}
<tr class="{$Classes.sequence}">
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$Classes.item.id}" title="{'Select class for removal.'|i18n( 'design/admin/class/classlist' )}" /></td>
    <td>{$Classes.item.identifier|class_icon( small, $Classes.item.name|wash )}&nbsp;<a href={concat( "/class/view/", $Classes.item.id )|ezurl}>{$Classes.item.name|wash}</a></td>
    <td class="number" align="right">{$Classes.item.id}</td>
    <td>{$Classes.item.identifier|wash}</td>
    <td>{content_view_gui view=text_linked content_object=$Classes.item.modifier.contentobject}</td>
    <td>{$Classes.item.modified|l10n( shortdatetime )}</td>
    <td class="number" align="right">{$Classes.item.object_count}</td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'There are no classes in this group.'|i18n( 'design/admin/class/classlist' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <div class="left">
    <input type="hidden" name = "CurrentGroupID" value="{$GroupID}" />
    <input type="hidden" name = "CurrentGroupName" value="{$group.name|wash}" />

    {section show=$class_count}
      <input class="button" type="submit" name="SetButton" value="{'Add metatags attributes for selected classes'|i18n( 'design/admin/metatags/classlist' )}" title="{'Add metatags attributes for selected classes'|i18n( 'design/admin/metatags/classlist' )}" />
      <input class="button" type="submit" name="UnsetButton" value="{'Remove metatags attributes for selected classes'|i18n( 'design/admin/metatags/classlist' )}" title="{'Remove metatags attributes for selected classes'|i18n( 'design/admin/metatags/classlist' )}" />
    {section-else}
      <input class="button-disabled" type="submit" name="SetButton" value="{'Add metatags attributes for selected classes'|i18n( 'design/admin/metatags/classlist' )}" disabled="disabled" />
      <input class="button-disabled" type="submit" name="UnsetButton" value="{'Remove metatags attributes for selected classes'|i18n( 'design/admin/metatags/classlist' )}" disabled="disabled" />
    {/section}
    </div>

    <div class="break"></div>
</div>


{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

