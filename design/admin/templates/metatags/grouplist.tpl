<div class="context-block">

{* DESIGN: Header START *}<div class="box-header">

<h1 class="context-title">{'Class groups (%group_count)'|i18n( 'design/admin/class/grouplist',, hash( '%group_count', $groups|count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">

<table class="list" cellspacing="0" summary="{'List of class groups'|i18n( 'design/admin/class/grouplist' )}">
<tr>
    <th>{'Name'|i18n( 'design/admin/class/grouplist' )}</th>
    <th>{'Modifier'|i18n( 'design/admin/class/grouplist' )}</th>
    <th>{'Modified'|i18n( 'design/admin/class/grouplist' )}</th>
</tr>

{section var=Groups loop=$groups sequence=array( bglight, bgdark )}
<tr class="{$Groups.sequence}">

    {* Name. *}
    <td>{$Groups.item.name|wash|classgroup_icon( small, $Groups.item.name|wash )}&nbsp;<a href={concat( $module.functions.classlist.uri, '/', $Groups.item.id)|ezurl}>{$Groups.item.name|wash}</a></td>

    {* Modifier. *}
    <td><a href={$Groups.item.modifier.contentobject.main_node.url_alias|ezurl}>{$Groups.item.modifier.contentobject.name|wash}</a></td>

    {* Modified. *}
    <td>{$Groups.item.modified|l10n( shortdatetime )}</td>

</tr>
{/section}
</table>

</div>
