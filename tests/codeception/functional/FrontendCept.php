<?php
$I = new TestDev($scenario);
$I->wantTo('test index page is working');
$I->amOnPage('/');
$I->canSee('Hi Phalcon!');