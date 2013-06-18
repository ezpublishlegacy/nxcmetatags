<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
//
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.2
// BUILD VERSION: 23601
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

$Module = array( "name" => "nxcMetatags",
                 "variable_parameters" => true );

$ViewList = array();

$ViewList["setclassmetatags"] = array(
    "functions" => array ( 'administrate' ),   
    "script" => "setclassmetatags.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( 'GroupID' ) );

$ViewList["unsetclassmetatags"] = array(
    "functions" => array ( 'administrate' ),   
    "script" => "unsetclassmetatags.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( 'GroupID' ) );

$ViewList["classlist"] = array(
    "functions" => array ( 'administrate' ),   
    "script" => "classlist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( 'class_group_id' ) );

$FunctionList = array();
$FunctionList['administrate'] = array();

?>
