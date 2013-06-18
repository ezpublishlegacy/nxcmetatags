<?php
//
//
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.3
// BUILD VERSION: 23650
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

$Module = $Params['Module'];
$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID = $Params["GroupID"];
$http = eZHTTPTool::instance();
$db = eZDB::instance();
$ini = eZINI::instance("nxcmetatags.ini");
$phpcmd = $ini->variable('UpdateScriptSettings','PhpCmd');
$deleteIDArray = $http->sessionVariable( "DeleteClassIDArray" );
$DeleteResult = array();
$alreadyRemoved = array();

if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    eZContentObject::clearCache();
    eZContentLanguage::expireCache();
    unset( $GLOBALS['eZExpiryHandlerInstance'] );
    unset( $GLOBALS['eZContentObjectDefaultLanguage'] ); //see bug #013625
    unset( $GLOBALS['eZContentCacheInfo'] ); // see bug #013625

    $userID = eZUser::currentUserID();
    foreach ( $deleteIDArray as $ClassID )
    {
        pclose(popen( "$phpcmd extension/nxcmetatags/scripts/update_metatags.php --action=add --classes-id=$ClassID --user-id=$userID > /dev/null 2>&1 &","r"));
//        pclose(popen( "$phpcmd extension/nxcmetatags/scripts/update_metatags.php --action=add --classes-id=$ClassID --user-id=$userID > var/log/nxcmetatags.log 2>&1 &","r"));
    }//end loop through chosen classes

    return $Module->redirectTo( '/metatags/classlist' );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    return $Module->redirectTo( '/metatags/classlist/' );
}

$canRemoveCount = 0;
foreach ( $deleteIDArray as $deleteID )
{
    $ClassObjectsCount = 0;
    $class = eZContentClass::fetch( $deleteID );
    if ( $class != null )
    {
        $class = eZContentClass::fetch( $deleteID );
        $ClassID = $class->attribute( 'id' );
        $ClassName = $class->attribute( 'name' );
        ++$canRemoveCount;
        $classObjects = eZContentObject::fetchSameClassList( $ClassID );
        $ClassObjectsCount = count( $classObjects );
        $item = array( "className" => $ClassName,
                       "is_removable" => true,
                       "objectCount" => $ClassObjectsCount );
        $DeleteResult[] = $item;
    }
}

$canRemove = ( $canRemoveCount > 0 );

$Module->setTitle( ezi18n( 'kernel/class', 'Update metatags attributes for classes %class_id', null, array( '%class_id' => $ClassID ) ) );
require_once( "kernel/common/template.php" );
$tpl = templateInit();

$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'GroupID', $GroupID );
$tpl->setVariable( 'DeleteResult', $DeleteResult );
$tpl->setVariable( 'already_removed', $alreadyRemoved );
$tpl->setVariable( 'can_remove', $canRemove );

$Result = array();
$Result['content'] = $tpl->fetch( "design:metatags/setclassmetatags.tpl" );
$Result['path'] = array( array( 'url' => '/metatags/classlist/',
                                'text' => ezi18n( 'kernel/class', 'Classes' ) ) );
?>
