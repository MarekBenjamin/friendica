<?php
/**
 * Name: TEST-ADDON: Authentication "allow all"
 * Description: For testing purpose only
 * Version: 1.0
 * Author: Philipp Holzer <admin@philipp.info>
 */

use Friendica\Core\Hook;
use Friendica\Model\User;

function authtest_install()
{
	Hook::register('authenticate', 'tests/Util/authtest/authtest.php', 'authtest_authenticate');
}

function authtest_authenticate($a,&$b)
{
	$b['authenticated'] = 1;
	$b['user_record']   = User::getById(42);
}