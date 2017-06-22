<?php 
$I = new FunctionalTester($scenario);
$I->wantTo('Check RMA plugin API validation');
$I->amOnPage("/");
$I->loginAsAdmin();
$I->amOnPage("/wp-admin");
$I->amOnPluginsPage();
$I->seePluginActivated('remote-member-authorization');
$I->amOnPage("/wp-admin/options-general.php?page=rma-settings");
$I->see('API key');
$I->selectOption('form input[id=authAPI]', 'API key');
$I->click('Save stuff');
$I->see('API key may not be empty');
$I->see('API key field name may not be empty');
$I->see('User data URI may not be empty');
$I->see('Set password URI may not be empty');
$I->see('Status field name may not be empty');
$I->see('Active status value may not be empty');