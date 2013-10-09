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
$tpl = eZTemplate::factory();
$http = eZHTTPTool::instance();

$Module->setTitle( ezpI18n::tr( 'kernel/class', 'Group list ' ) );

$groups = eZContentClassGroup::fetchList( false, true );
$tpl->setVariable('groups', $groups);
$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] = $tpl->fetch( "design:metatags/grouplist.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Classes' ) ),
                         array( 'url' => false,
                                'text' => $groupName ) );
?>
