#!/usr/bin/env php
<?php
//
// Created on: <02-Jun-2009 15:00:00 sa>
//
// file  extension/nxcmetatags/update_metatags.php

// script initializing
require 'autoload.php';

set_time_limit( 0 );

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                         "This script adds or removes metatags attributes for the chosen classes and their objects.\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[action:][classes-id:][user-id:]",
                                      "",
                                      array( 'action' => "Either 'add' or 'remove').",
                                             'classes-id' => "Classes IDs (separated by comma ',').",
                                             'user-id' => "User ID ('14' - admin by default)."
                                             ),
                                      false );
$script->initialize();

$action = $scriptOptions['action'] ? trim($scriptOptions['action']) : false;

if ( $action !='remove' && $action !='add')
{
    $script->showHelp();
    $script->shutdown( 1 );
}

$ClassesID  = $scriptOptions[ 'classes-id' ] ? trim( $scriptOptions[ 'classes-id' ] ) : false;
$UserID = $scriptOptions[ 'user-id' ] ? trim( $scriptOptions[ 'user-id' ] ) : 14;
$deleteIDArray = $ClassesID ? explode( ',', $ClassesID ) : false;

$cli->output( 'user:'.$UserID.' class:'.$ClassesID.' action: '.$action );

// Log in user
$user = eZUser::fetch( $UserID );
if ( $user )
{
    eZUser::setCurrentlyLoggedInUser( $user, $user->attribute( 'id' ) );
}
else
{
    $cli->error( 'Could not fetch admin user object' );
    $script->shutdown( 1 );
    return;
}

$language = eZContentLanguage::topPriorityLanguage();
if ( $language )
{
    $EditLanguage = $language->attribute( 'locale' );
}
else
{
    $cli->error( 'Undefined default language' );
    $script->shutdown( 1 );
    return;
}

$cli->output( 'default language:'.$EditLanguage );

if ( $action == 'add' )
{
$cli->output( 'action:'.$action );
    foreach ( $deleteIDArray as $ClassID )
    {
        $hasMetatags = false;

        $class = eZContentClass::fetch( $ClassID, true, eZContentClass::VERSION_STATUS_TEMPORARY );
        // If temporary version does not exist fetch the current and add temperory class to corresponding group
        if ( !is_object( $class ) or $class->attribute( 'id' ) == null )
        {
            $class = eZContentClass::fetch( $ClassID, true, eZContentClass::VERSION_STATUS_DEFINED );
            if( is_null( $class ) ) // Class does not exist
            {
                return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
            }
            $classGroups= eZContentClassClassGroup::fetchGroupList( $ClassID, eZContentClass::VERSION_STATUS_DEFINED );
            foreach ( $classGroups as $classGroup )
            {
                $groupID = $classGroup->attribute( 'group_id' );
                $groupName = $classGroup->attribute( 'group_name' );
                $ingroup = eZContentClassClassGroup::create( $ClassID, eZContentClass::VERSION_STATUS_TEMPORARY, $groupID, $groupName );
                $ingroup->store();
            }
        }

        if ( $class ) 
        {
           $attributes = $class->fetchAttributes();
           $class->setAttribute( 'version', eZContentClass::VERSION_STATUS_TEMPORARY );
           foreach ( $attributes as $attribute ){
               if ( $attribute->Identifier == 'meta_keywords' )
               {
                  $hasMetatags = true;
                  break;
               }
           }

           if ( $hasMetatags ) 
           {
               continue;
           }
           else
           {
             $new_datatype ='ezstring';

             //add title attribute
             $new_attribute = eZContentClassAttribute::create( $ClassID, $new_datatype, array('identifier' => 'head_title'), $EditLanguage );

             $new_attribute->setName( 'Page Title', $EditLanguage );
             $new_attribute->setName( 'Page Title', 'eng-GB' );
             $new_attribute->setAttribute( 'placement', count($attributes)+1 );

             $dataType = $new_attribute->dataType();
             $dataType->initializeClassAttribute( $new_attribute );
             $new_attribute->store();
             $attributes[] = $new_attribute;

             //add meta_keywords attribute
             $new_attribute1 = eZContentClassAttribute::create( $ClassID, $new_datatype, array('identifier' => 'meta_keywords'), $EditLanguage );
             $new_attribute1->setName( 'Meta Keywords', $EditLanguage );
             $new_attribute1->setName( 'Meta Keywords', 'eng-GB' );
             $new_attribute1->setAttribute( 'placement', count($attributes)+1 );

             $dataType = $new_attribute1->dataType();
             $dataType->initializeClassAttribute( $new_attribute1 );
             $new_attribute1->store();
             $attributes[] = $new_attribute1;

             //add meta_description attribute
             $new_attribute2 = eZContentClassAttribute::create( $ClassID, $new_datatype, array('identifier' => 'meta_description'), $EditLanguage );
             $new_attribute2->setName( 'Meta Description', $EditLanguage );
             $new_attribute2->setName( 'Meta Description', 'eng-GB' );
             $new_attribute2->setAttribute( 'placement', count($attributes)+1 );

             $dataType = $new_attribute2->dataType();
             $dataType->initializeClassAttribute( $new_attribute2 );
             $new_attribute2->store();
             $attributes[] = $new_attribute2;

             $class->storeDefined( $attributes );

             $objectCount = eZContentObject::fetchSameClassListCount( $ClassID );
             if ( $objectCount > 0 ) 
             {
                 // Add attribute to objects of this class.
/* //works for for eZ Publish 4.1 but doesn't work for earlyer versions ( f.e. 4.0.1 )
                 $new_attribute->initializeObjectAttributes( $objects );
                 $new_attribute1->initializeObjectAttributes( $objects );
                 $new_attribute2->initializeObjectAttributes( $objects );
*/
		     if ( !is_array( $objects ) )
		     {
		         $objects = eZContentObject::fetchSameClassList( $ClassID );
		     }

		     $classAttributeIDs = array ( $new_attribute->ID, $new_attribute1->ID, $new_attribute2->ID );
                     foreach( $classAttributeIDs as $classAttributeID )
			{
			     foreach ( $objects as $object )
			     {
				 $contentobjectID = $object->attribute( 'id' );
				 $objectVersions = $object->versions();
				 foreach ( $objectVersions as $objectVersion )
				 {
				     $translations = $objectVersion->translations( false );
				     $version = $objectVersion->attribute( 'version' );
				     foreach ( $translations as $translation )
				     {
				         $objectAttribute = eZContentObjectAttribute::create( $classAttributeID, $contentobjectID, $version, $translation );
				         $objectAttribute->setAttribute( 'language_code', $translation );
				         $objectAttribute->initialize();
				         $objectAttribute->store();
				         $objectAttribute->postInitialize();
				     }
				 }
			     }
			}
             }

           }
        }
        else
        {
            eZContentClassClassGroup::removeClassMembers( $ClassID, eZContentClass::VERSION_STATUS_TEMPORARY );
        }

    }//end loop through chosen classes
}

if ( $action == 'remove' )
{
$cli->output( 'action:'.$action );

    foreach ( $deleteIDArray as $ClassID )
    {
        $hasMetatags = false;

        $class = eZContentClass::fetch( $ClassID, true, eZContentClass::VERSION_STATUS_TEMPORARY );
        // If temporary version does not exist fetch the current and add temperory class to corresponding group
        if ( !is_object( $class ) or $class->attribute( 'id' ) == null )
        {
            $class = eZContentClass::fetch( $ClassID, true, eZContentClass::VERSION_STATUS_DEFINED );
            if( is_null( $class ) ) // Class does not exist
            {
                return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
            }
            $classGroups= eZContentClassClassGroup::fetchGroupList( $ClassID, eZContentClass::VERSION_STATUS_DEFINED );
            foreach ( $classGroups as $classGroup )
            {
                $groupID = $classGroup->attribute( 'group_id' );
                $groupName = $classGroup->attribute( 'group_name' );
                $ingroup = eZContentClassClassGroup::create( $ClassID, eZContentClass::VERSION_STATUS_TEMPORARY, $groupID, $groupName );
                $ingroup->store();
            }
        }

        if ( $class ) 
        {
           $attributes = $class->fetchAttributes();
           $refreshedAttributes = array();
           $class->setAttribute( 'version', eZContentClass::VERSION_STATUS_TEMPORARY );

           $head_title_attr = null;
           $meta_keyword_attr = null;
           $meta_description_attr = null;
           foreach ( $attributes as $attribute ){
               if ( $attribute->Identifier == 'head_title' )
               {
                  $head_title_attr = $attribute;
                  $hasMetatags = true;
               }
               elseif ( $attribute->Identifier == 'meta_keywords' )
               {
                  $meta_keyword_attr = $attribute;
                  $hasMetatags = true;
               }
               elseif ( $attribute->Identifier == 'meta_description' )
               {
                  $meta_description_attr = $attribute;
                  $hasMetatags = true;
               } 
               else
               {
                  $refreshedAttributes[] = $attribute;
               }
           }

           if ( $hasMetatags ) 
           {
             if ( $head_title_attr !== null ){
                 $head_title_attr->removeThis(true);
             }
             if ( $meta_keyword_attr !== null ){
                 $meta_keyword_attr->removeThis(true);
             }
             if ( $meta_description_attr !== null ){
                 $meta_description_attr->removeThis(true);
             }
             $class->storeDefined( $refreshedAttributes );
           }
        } 
        else
        {
            eZContentClassClassGroup::removeClassMembers( $ClassID, eZContentClass::VERSION_STATUS_TEMPORARY );
        }

    }//end loop through chosen classes
}

$cli->output( "Done." );
$script->shutdown();

?>
