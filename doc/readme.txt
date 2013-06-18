Ncxmetatags extension allows to set HTML 
metatags <meta "keywords"/>, <meta "description"/>
and <title> tag in the HTML head of the page.

Installation.
~~~~~~~~~~~~~

- Copy nxcmetatags extension to extension/ directory of eZPublish.
  or
  #cd "eZ Publish root dir"/extension
  #svn co http://svn.nxc.no/extensions/nxcmetatags
- Set correct command that launches php-scripts in your system by modifying 
  PhpCmd parameter in nxcmetatags/settings/nxcmetatags.ini.append.php
  ( PhpCmd will be "php" in most cases, but you may need "php5" 
  or something else there ).
- Enable nxcmetatags extension through eZ Publish web interface.
- Clear the cache.


Usage.
~~~~~~

- Navigate to "Setup" in eZ Publish admin interface.
- Follow "Metatags" link in left menu.
- Select the content classes you need to have controlled metatags.
- Press "Add metatags attributes for selected classes" button.
- After operation confirming the classes and objects updating script will be 
  launched in background. The script execution time depends of the complexity 
  and amount of the existent content objects for chosen classes and 
  also depends of the overall database size and the server load. 
  This should not take more than several minutes. You can navigate to the 
  correspondent class view to see if it already processed. Three new 
  text line attributes should appear for that class - "head_title", 
  "meta_keywords" and "meta_description".
- Now you can try to create or edit the content objects of chosen class.
  Text provided in "head_title" attribute for an object overrides default 
  content of the <title> tag in the full view page of that object.
  Content of the "meta_keywords" and "meta_description" appears in 
  <meta "keywords"/> and <meta "description"/> in the full view of that 
  object.
  If attribute left blank than default eZ Publish content will 
  be used for the correspondent tag.
