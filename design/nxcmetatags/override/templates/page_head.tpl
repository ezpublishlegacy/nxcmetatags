{def
	$enable_help = true() 
	$enable_link = true()
}

{if is_set($module_result.content_info.persistent_variable.site_title)}
    {set scope=root site_title=$module_result.content_info.persistent_variable.site_title}
{else}
	{let name=Path
		path=$module_result.path
		reverse_path=array()}
		{if is_set($pagedata.path_array)}
			{set path = $pagedata.path_array}
		{elseif is_set( $module_result.title_path )}
			{set path = $module_result.title_path}
		{/if}
		{section loop=$:path}
			{set reverse_path=$:reverse_path|array_prepend($:item)}
		{/section}
	{/let}

	{def
		$site_title      = $site.title
		$site_title_path = false()
		$path            = $module_result.path
		$reverse_path    = array()
	}

	{if is_set( $pagedata.path_array )}
		{set $path = $pagedata.path_array}
	{elseif is_set( $module_result.title_path )}
		{set $path = $module_result.title_path}
	{/if}

	{foreach $path as $item}
		{set $reverse_path = $reverse_path|array_prepend( $item )}
	{/foreach}

	{foreach $reverse_path as $item}
		{if ne( $item.node_id, 2 )}
			{if eq( $site_title_path, false() )}
				{set $site_title_path=$item.text}
			{else}
				{set $site_title_path=concat( $site_title_path, ' / ', $item.text )}
			{/if}
		{/if}
	{/foreach}

	{if ne( $site_title_path, false() )}
		{set $site_title = concat( $site_title_path, ' - ', $site_title )}
	{/if}
	{undef $site_title_path $path $reverse_path}

{/if}

    {if gt($module_result.node_id, 0) }
       {def $cur_node=fetch('content', 'node', hash('node_id', $module_result.node_id ) )}
       {if is_object($cur_node) }
         {if and( is_set($cur_node.data_map.head_title.data_text),
                  is_string($cur_node.data_map.head_title.data_text),
                  gt( $cur_node.data_map.head_title.data_text|count_chars(), 0 )
                ) }
           {set $site_title=$cur_node.data_map.head_title.data_text}
         {/if}
         {def $meta = $site.meta }
         {if and( is_set($cur_node.data_map.meta_keywords.data_text),
                  is_string($cur_node.data_map.meta_keywords.data_text),
                  gt( $cur_node.data_map.meta_keywords.data_text|count_chars(), 0 )
                  ) }
           {set $meta=$meta|merge(hash('keywords',$cur_node.data_map.meta_keywords.data_text)) }
         {/if}
         {if and( is_set($cur_node.data_map.meta_description.data_text),
                  is_string($cur_node.data_map.meta_description.data_text),
                  gt( $cur_node.data_map.meta_description.data_text|count_chars(), 0 )
                ) }
           {set $meta=$meta|merge(hash('description',$cur_node.data_map.meta_description.data_text)) }
         {/if}
       {/if}
    {/if}
    <title>{$site_title}</title>

    {if and(is_set($#Header:extra_data),is_array($#Header:extra_data))}
      {section name=ExtraData loop=$#Header:extra_data}
      {$:item}
      {/section}
    {/if}

    {* check if we need a http-equiv refresh *}
    {if $site.redirect}
    <meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />

    {/if}
    {foreach $site.http_equiv as $key => $item}
        <meta name="{$key|wash}" content="{$item|wash}" />
    {/foreach}

    {foreach $meta as $key => $item}
		{if is_set( $module_result.content_info.persistent_variable[$key] )}
		    <meta name="{$key|wash}" content="{$module_result.content_info.persistent_variable[$key]|wash}" />
		{else}
		    <meta name="{$key|wash}" content="{$item|wash}" />
		{/if}
    {/foreach}
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <meta name="generator" content="eZ Publish" />

    {* DDSA Analytics Integration - BEGIN - http://support.nxc.no/browse/TIGSUP-2838 *}

    {* The following meta tags should be placed within the header of each page to be tracked. *}
    {if $module_result.uri|contains( 'user/register' )}
        <META NAME="WT.si_n" CONTENT="JoyCart" />
        <META NAME="WT.si_x" CONTENT="1" />
        <META NAME="WT.cg_n" CONTENT="Registration" />
        <META NAME="WT.ti" CONTENT="Registration Form" />
    {elseif $module_result.uri|contains( 'user/success' )}
        {def
            $postal_code  = cond( is_set( $tbe_user.profile.PrimaryAddress ), $tbe_user.profile.PrimaryAddress.PostalCode, "" )
            $state        = cond( is_set( $tbe_user.profile.PrimaryAddress ), $tbe_user.profile.PrimaryAddress.State, "" )
            $country_code = cond( is_set( $tbe_user.profile.PrimaryAddress ), $tbe_user.profile.PrimaryAddress.CountryCode, "" )
            $country_list = tbe_ows( Information, FetchCountryList )
            $country      = cond( $country_list, $country_list[$country_code], '' )
        }
        <META NAME="WT.si_n" CONTENT="JoyCart" />
        <META NAME="WT.si_x" CONTENT="2" />
        <META NAME="WT.cg_n" CONTENT="Registration" />
        <META NAME="WT.ti" CONTENT="Registration Confirmation" />
        <META NAME="DCSext.MemberPostalCode" CONTENT="{$postal_code}" />
        <META NAME="DCSext.MemberStateCode" CONTENT="{$state}" />
        <META NAME="DCSext.MemberCountryCode" CONTENT="{$country}" />
        <META NAME="DCSext.MemberID" CONTENT="{$tbe_user.profile.membership_list.JOL.Number}" />

        {undef $postal_code $stat $country_code $country_list $country}
    {else}
        <meta name="WT.ti" content="{$site_title}" /> {* place page title here *}
        <meta name="WT.cg_n" content="{$site_title}" /> {* place content group name here *}

    {/if}

    {*SEARCH ENGINE AND CAMPAIGN TRACKING *}
    {def
        $source      = cond( ezhttp_hasvariable( 'source', 'get' ), ezhttp( 'source', 'get' ), false() )
        $ad_creative = cond( ezhttp_hasvariable( 'AdCreative', 'get' ), ezhttp( 'AdCreative', 'get' ), false() )
        $emc         = cond( ezhttp_hasvariable( 'emc', 'get' ), ezhttp( 'emc', 'get' ), false() )
    }

    {if $source}
        <meta name="WT.mc_n" Content="{$source|wash}" />
        <meta name="WT.srch" Content="1" />
        <meta name="WT.mc_id" Content="{$source|wash}" />
        <meta name="WT.vr.ipd_se" Content="{$source|wash}" />
    {/if}

    {if $emc}
        <meta name="WT.mc_n" Content="{$emc|wash}" />
        <meta name="WT.mc_id" Content="{$emc|wash}" />
    {/if}

    {if $ad_creative}
        <meta name="WT.vr.rac_cr" Content="{$ad_creative|wash}" />
    {/if}

    {undef $source $ad_creative $emc}
    {* TRACKING END *}

{if $enable_link}
    {include uri="design:link.tpl" enable_help=$enable_help enable_link=$enable_link}
{/if}
