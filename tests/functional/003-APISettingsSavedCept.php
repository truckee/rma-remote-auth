<?php 
$I = new FunctionalTester($scenario);
$I->wantTo('Check RMA plugin API settings');
$I->amOnPage("/");
$I->loginAsAdmin();
$I->amOnPage("/wp-admin");
$I->amOnPluginsPage();
$I->seePluginActivated('remote-member-authorization');
$I->amOnPage("/wp-admin/options-general.php?page=rma-settings");
$I->see('API key');
$I->selectOption('form input[id=authAPI]', 'API key');
$I->fillField('rmaApiKey', '3e2ec79352d2e9cbd76ad409d968ee435af6695c');
$I->fillField('rmaKeyFieldName', 'apikey');
$I->fillField('rmaDataURI', 'http://wprest/api/get_users');
$I->fillField('rmaSetPasswordURI', 'http://wprest/api/setMemberPassword');
$I->fillField('rmaStatusName', 'enabled');
$I->fillField('rmaStatusValue', 'true');
$I->click('Save stuff');
$I->see('Settings saved.');