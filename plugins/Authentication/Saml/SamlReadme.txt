=======================
Setting up the Saml Authentication plugin for phpScheduleIt version 2.4
=======================
Changes:
2012/05/17 - Created by Gail Preuninger

========================
Requirements:
Need to have SAML 2.0 Identity Provider that can access authentication 
data store and communicate with SAML 2.0 Service Provider
see http://simplesamlphp.org/

Install SAML 2.0 Service Provider on the same server where phpScheduleIt
is installed.  Obtain this software from:
http://code.google.com/p/simplesamlphp/downloads/list

This plugin was tested with version 1.8.2 SimpleSAMLphp

Verify that communication between IdP and SP works.  On simpleSAMLphp
installation page, go to Authentication tab, click on "Test configured
authentication sources", and choose your SP entity.
Enter your username and password that is in data store that IdP references.

==========================
Installation Instructions

1) Unpack the Saml plugin to the DIRECTORY_ROOT/plugins/Authentication/ directory

2) make changes to the following two core phpScheduleIt files:
 2.1)  DIRECTORY_ROOT/Presenters/LoginPresenter.php
  In setAuthentication() function:
  Add the following code after calling PluginManager::Instance()->LoadAuthentication():
        if (is_null($authentication))
        {
            $this->authentication = PluginManager::Instance()->LoadAuthentication();
            // add new code here
            $pluginName =
            Configuration::Instance()->GetSectionKey(ConfigSection::PLUGINS, 
                    ConfigKeys:: PLUGIN_AUTHENTICATION);
            if ($pluginName == "Saml") 
            {
                $this->_page->Set('SAMLphpLogin',true);
            }
            else{
                $this->_page->Set('SAMLphpLogin',false);
            }
        } 

 2.2) DIRECTORY_ROOT/tpl/login.tpl
    Use {if ! $SAMLphpLogin}
        HTML code
        {/if}  
    to filter out username and password fields as well other links.  Please
    refer to saml.login.sample.tpl as a reference on how to modify login.tpl
 
3) Go to DIRECTORY_ROOT/plugins/Authentication/Saml/
   Copy Saml.config.dist.php to Saml.config.php and change the settings to 
   in Saml.config.php to reflect your SimpleSAMLphp environment

4) In the phpScheduleIt ROOT_DIRECTORY/config/config.php file, 
  set $conf['settings']['plugins']['Authentication'] = 'Saml'. 
  Note: make sure that you do not enable self-registration.  
        Authentication is only through SAML Single Sign On.
  set $conf['settings']['allow.self.registration'] = 'false';


