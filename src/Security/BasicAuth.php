<?php
/**
 * @copyright Copyright (C) 2010-2021, the Friendica project
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace Friendica\Security;

use Exception;
use Friendica\Core\Hook;
use Friendica\Core\Logger;
use Friendica\Core\Session;
use Friendica\Database\DBA;
use Friendica\DI;
use Friendica\Model\User;
use Friendica\Network\HTTPException\UnauthorizedException;
use Friendica\Util\DateTimeFormat;

/**
 * Authentification via the basic auth method
 */
class BasicAuth
{
	/**
	 * @var bool|int
	 */
	protected static $current_user_id = 0;
	/**
	 * @var array
	 */
	protected static $current_token = [];

	/**
	 * Get current user id, returns 0 if $login is set to false and not logged in.
	 * When $login is true, the execution will stop when not logged in.
	 *
	 * @param bool $login Perform a login request if "true"
	 *
	 * @return int User ID
	 */
	public static function getCurrentUserID(bool $login)
	{
		if (empty(self::$current_user_id)) {
			self::$current_user_id = self::getUserIdByAuth($login);
		}

		return (int)self::$current_user_id;
	}

	/**
	 * Fetch a dummy application token
	 *
	 * @return array token
	 */
	public static function getCurrentApplicationToken()
	{
		if (empty(self::getCurrentUserID(true))) {
			return [];
		}

		if (!empty(self::$current_token)) {
			return self::$current_token;
		}

		self::$current_token = [
			'uid'        => self::$current_user_id,
			'id'         => 0,
			'name'       => api_source(),
			'website'    => '',
			'created_at' => DBA::NULL_DATETIME,
			'read'       => true,
			'write'      => true,
			'follow'     => true,
			'push'       => false];

		return self::$current_token;
	}

	/**
	 * Fetch the user id via the auth header information
	 *
	 * @param boolean $do_login Perform a login request if not logged in
	 *
	 * @return integer User ID
	 */
	private static function getUserIdByAuth(bool $do_login = true):int
	{
		$a = DI::app();
		Session::set('allow_api', false);
		self::$current_user_id = 0;

		// workaround for HTTP-auth in CGI mode
		if (!empty($_SERVER['REDIRECT_REMOTE_USER'])) {
			$userpass = base64_decode(substr($_SERVER["REDIRECT_REMOTE_USER"], 6));
			if (strlen($userpass)) {
				list($name, $password) = explode(':', $userpass);
				$_SERVER['PHP_AUTH_USER'] = $name;
				$_SERVER['PHP_AUTH_PW'] = $password;
			}
		}

		$user = $_SERVER['PHP_AUTH_USER'] ?? '';
		$password = $_SERVER['PHP_AUTH_PW'] ?? '';
	
		// allow "user@server" login (but ignore 'server' part)
		$at = strstr($user, "@", true);
		if ($at) {
			$user = $at;
		}
	
		// next code from mod/auth.php. needs better solution
		$record = null;
	
		$addon_auth = [
			'username' => trim($user),
			'password' => trim($password),
			'authenticated' => 0,
			'user_record' => null,
		];
	
		/*
		* An addon indicates successful login by setting 'authenticated' to non-zero value and returning a user record
		* Addons should never set 'authenticated' except to indicate success - as hooks may be chained
		* and later addons should not interfere with an earlier one that succeeded.
		*/
		Hook::callAll('authenticate', $addon_auth);
	
		if ($addon_auth['authenticated'] && !empty($addon_auth['user_record'])) {
			$record = $addon_auth['user_record'];
		} else {
			try {
				$user_id = User::getIdFromPasswordAuthentication(trim($user), trim($password), true);
				$record = DBA::selectFirst('user', [], ['uid' => $user_id]);
			} catch (Exception $ex) {
				$record = [];
			}			
		}
	
		if (empty($record)) {
			if (!$do_login) {
				return 0;
			}
			Logger::debug('failed', ['module' => 'api', 'action' => 'login', 'parameters' => $_SERVER]);
			header('WWW-Authenticate: Basic realm="Friendica"');
			throw new UnauthorizedException("This API requires login");
		}
	
		// Don't refresh the login date more often than twice a day to spare database writes
		$login_refresh = strcmp(DateTimeFormat::utc('now - 12 hours'), $record['login_date']) > 0;
	
		DI::auth()->setForUser($a, $record, false, false, $login_refresh);
	
		Session::set('allow_api', true);
	
		Hook::callAll('logged_in', $a->user);
	
		if (Session::get('allow_api')) {
			self::$current_user_id = local_user();
		} else {
			self::$current_user_id = 0;
		}
		return self::$current_user_id;
	}	
}
