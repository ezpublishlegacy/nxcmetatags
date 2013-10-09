{default enable_help=true() enable_link=true() canonical_link=true()}

{def $current_node = false()}
{if and(is_set($module_result.node_id), $module_result.node_id|gt(0))}
    {set $current_node = fetch('content','node',hash('node_id', $module_result.node_id))}
{/if}

{if is_set($module_result.content_info.persistent_variable.site_title)}
    {set-block scope=root variable=site_title}{$module_result.content_info.persistent_variable.site_title|wash}{/set-block}
{elseif and( $current_node,
             is_set($current_node.data_map.head_title),
             is_string($current_node.data_map.head_title.content),
             gt( $current_node.data_map.head_title.content|count_chars(), 0 ))}
    {set-block scope=root variable=site_title}{$current_node.data_map.head_title.content|wash}{/set-block}
{else}
    {let name=Path
         path=$module_result.path
         reverse_path=array()}
        {if is_set($pagedata.path_array)}
            {set path=$pagedata.path_array}
        {elseif is_set($module_result.title_path)}
            {set path=$module_result.title_path}
        {/if}
        {section loop=$:path}
            {set reverse_path=$:reverse_path|array_prepend($:item)}
        {/section}

        {set-block scope=root variable=site_title}
            {section loop=$Path:reverse_path}{$:item.text|wash}{delimiter} / {/delimiter}{/section} - {$site.title|wash}
        {/set-block}

    {/let}
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

{foreach $site.meta as $key => $item}
    {if is_set( $module_result.content_info.persistent_variable[$key] )}
        <meta name="{$key|wash}" content="{$module_result.content_info.persistent_variable[$key]|wash}" />
    {elseif and( $key|eq('description'),
                 $current_node,
                 is_set($current_node.data_map.meta_description),
                 is_string($current_node.data_map.meta_description.content),
                 gt( $current_node.data_map.meta_description.content|count_chars(), 0 ))}
        <meta name="{$key|wash}" content="{$current_node.data_map.meta_description.content|wash}" />
    {elseif and( $key|eq('keywords'),
                 $current_node,
                 is_set($current_node.data_map.meta_keywords),
                 is_string($current_node.data_map.meta_keywords.content),
                 gt( $current_node.data_map.meta_keywords.content|count_chars(), 0 ))}
        <meta name="{$key|wash}" content="{$current_node.data_map.meta_keywords.content|wash}" />
    {else}
        <meta name="{$key|wash}" content="{$item|wash}" />
    {/if}
{/foreach}

{* Prefer chrome frame on IE 8 and lower, or at least as new engine as possible *}
<!--[if lt IE 9 ]>
    <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
<![endif]-->

<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<meta name="generator" content="eZ Publish" />

{if $canonical_link}
    {include uri="design:canonical_link.tpl"}
{/if}

{if $enable_link}
    {include uri="design:link.tpl" enable_help=$enable_help enable_link=$enable_link}
{/if}

{/default}
