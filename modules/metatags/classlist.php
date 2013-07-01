<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
//
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.0
// BUILD VERSION: 20988
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//


$Module = $Params['Module'];

$GroupID = (isset( $Params['class_group_id'] ) && $Params['class_group_id'] != '0') ? $Params['class_group_id'] : 1; // 1 mean Content group. TODO: take it from ini

$http = eZHTTPTool::instance();
$http->setSessionVariable( 'FromGroupID', $GroupID );

if ( $http->hasPostVariable( "SetButton" ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $setIDArray = $http->postVariable( 'DeleteIDArray' );
        if ( $setIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteClassIDArray', $setIDArray );
            $Module->redirectTo( $Module->functionURI( 'setclassmetatags' ) . '/'  . $GroupID . '/' );
        }
    }
}

if ( $http->hasPostVariable( "UnsetButton" ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        if ( $deleteIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteClassIDArray', $deleteIDArray );
            $Module->redirectTo( $Module->functionURI( 'unsetclassmetatags' ) . '/'  . $GroupID . '/' );
        }
    }
}


if ( !isset( $TemplateData ) or !is_array( $TemplateData ) )
{
    $TemplateData = array( array( "name" => "groupclasses",
                                  "http_base" => "ContentClass",
                                  "data" => array( "command" => "groupclass_list",
                                                   "type" => "class" ) ) );
}

$Module->setTitle( ezpI18n::tr( 'kernel/class', 'Class list of group' ) . ' ' . $GroupID );
require_once( "kernel/common/template.php" );
$tpl = templateInit();

$user = eZUser::currentUser();
foreach( $TemplateData as $tpldata )
{
    $tplname = $tpldata["name"];

    $groupInfo =  eZContentClassGroup::fetch( $GroupID );

    if( !$groupInfo )
    {
       return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

    $list = eZContentClassClassGroup::fetchClassList( 0, $GroupID, $asObject = true );
    $groupModifier = eZContentObject::fetch( $groupInfo->attribute( 'modifier_id') );
    $tpl->setVariable( $tplname, $list );
    $tpl->setVariable( "class_count", count( $list ) );
    $tpl->setVariable( "GroupID", $GroupID );
    $tpl->setVariable( "group", $groupInfo );
    $tpl->setVariable( "group_modifier", $groupModifier );
}

$group = eZContentClassGroup::fetch( $GroupID );
$groupName = $group->attribute( 'name' );


$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] = $tpl->fetch( "design:metatags/classlist.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Classes' ) ),
                         array( 'url' => false,
                                'text' => $groupName ) );
?>
