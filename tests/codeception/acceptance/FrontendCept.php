<?php 
$I = new WebDev($scenario);
$I->wantTo('perform actions and see result');
$I->amOnPage('/');
$I->see('Hi Phalcon!');
$I->amOnPage('/index/about');
$I->see('About');
$I->amOnPage('/index/fake');
$I->see('404');