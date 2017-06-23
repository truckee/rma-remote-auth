<?php 
$I = new FunctionalTester($scenario);
$I->wantTo('Check RMA plugin HTTP validation');
$I->amOnPage("/");
$I->loginAsAdmin();
$I->amOnPage("/wp-admin");
$I->amOnPluginsPage();
$I->seePluginActivated('remote-member-authorization');
$I->amOnPage("/wp-admin/options-general.php?page=rma-settings");
$I->see('API key');
$I->selectOption('form input[id=authHTTP]', 'HTTP Basic');
$I->click('Save stuff');
$I->see('Username may not be empty');
$I->see('Password may not be empty');
$I->see('User data URI may not be empty');
$I->see('Set password URI may not be empty');
$I->see('Status field name may not be empty');
$I->see('Active status value may not be empty');