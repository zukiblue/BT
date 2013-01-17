<?php


$g_script_login_cookie = null;
$g_cache_anonymous_user_cookie_string = null;
$g_cache_cookie_valid = null;
$g_cache_current_user_id = null;

/**
 * Return true if there is a currently logged in and authenticated user, false otherwise
 *
 * @param boolean auto-login anonymous user
 * @return bool
 * @access public
 */
function auth_is_user_authenticated() {
  
	global $g_cache_cookie_valid, $g_login_anonymous;
	if( $g_cache_cookie_valid == true ) {
		return $g_cache_cookie_valid;
	}
        $g_cache_cookie_valid = auth_is_cookie_valid( auth_get_current_user_cookie( $g_login_anonymous ) );
       // $g_cache_cookie_valid = true;
        
	return $g_cache_cookie_valid;
}


/**
 * is cookie valid?
 * @param string $p_cookie_string
 * @return bool
 * @access public
 */
function auth_is_cookie_valid( $p_cookie_string ) {
	global $g_cache_current_user_id;

	# fail if DB isn't accessible
	if( !db_is_connected() ) {
		return false;
	}

	# fail if cookie is blank
	if( '' === $p_cookie_string ) {
		return false;
	}

	# succeeed if user has already been authenticated
	if( null !== $g_cache_current_user_id ) {
		return true;
	}

	if( user_search_cache( 'cookie_string', $p_cookie_string ) ) {
		return true;
	}

	# look up cookie in the database to see if it is valid
	$t_user_table = db_get_table( 'mantis_user_table' );

	$query = "SELECT *
				  FROM $t_user_table
				  WHERE cookie_string=" . db_param();
	$result = db_query_bound( $query, Array( $p_cookie_string ) );

	# return true if a matching cookie was found
	if( 1 == db_num_rows( $result ) ) {
		user_cache_database_result( db_fetch_array( $result ) );
		return true;
	} else {
		return false;
	}
}

/**
 * Return the current user login cookie string,
 * note that the cookie cached by a script login superceeds the cookie provided by
 *  the browser. This shouldn't normally matter, except that the password verification uses
 *  this routine to bypass the normal authentication, and can get confused when a normal user
 *  logs in, then runs the verify script. the act of fetching config variables may get the wrong
 *  userid.
 * if no user is logged in and anonymous login is enabled, returns cookie for anonymous user
 * otherwise returns '' (an empty string)
 *
 * @param boolean auto-login anonymous user
 * @return string current user login cookie string
 * @access public
 */
function auth_get_current_user_cookie( $p_login_anonymous=true ) {
	global $g_script_login_cookie, $g_cache_anonymous_user_cookie_string;

	# if logging in via a script, return that cookie
	if( $g_script_login_cookie !== null ) {
		return $g_script_login_cookie;
	}

	# fetch user cookie
	$t_cookie_name = config_get( 'string_cookie' );
	$t_cookie = gpc_get_cookie( $t_cookie_name, '' );

	# if cookie not found, and anonymous login enabled, use cookie of anonymous account.
	if( is_blank( $t_cookie ) ) {
		if( $p_login_anonymous && ON == config_get( 'allow_anonymous_login' ) ) {
			if( $g_cache_anonymous_user_cookie_string === null ) {
				if( function_exists( 'db_is_connected' ) && db_is_connected() ) {

					# get anonymous information if database is available
					$query = 'SELECT id, cookie_string FROM ' . db_get_table( 'users' ) . ' WHERE username = ' . db_param();
					$result = db_query_bound( $query, Array( config_get( 'anonymous_account' ) ) );

					if( 1 == db_num_rows( $result ) ) {
						$row = db_fetch_array( $result );
						$t_cookie = $row['cookie_string'];

						$g_cache_anonymous_user_cookie_string = $t_cookie;
						$g_cache_current_user_id = $row['id'];
					}
				}
			} else {
				$t_cookie = $g_cache_anonymous_user_cookie_string;
			}
		}
	}

	return $t_cookie;
}

class auth {
    
    #var $g_script_login_cookie = null;
    #var $g_cache_anonymous_user_cookie_string = null;
    #var $cache_cookie_valid = null;
    #var $cache_current_user_id = null;

    static function init() {
        if(!($auth = new auth()))
            return null;

        return $auth;
    }

    function auth() {
        //
    }
}

?>
