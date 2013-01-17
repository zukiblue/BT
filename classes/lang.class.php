<?php

# Cache of localization strings in the language specified by the last
# lang_load call
$languagestrings = array();
# stack for language overrides
#$g_lang_overrides = array();
# To be used in custom_strings_inc.php :
$active_language = '';

// Loads the specified language and stores it in $g_lang_strings, to be used by lang_get
function lang_load( $language ) {
        global $languagestrings, $active_language;

	$active_language = $language;
	if( isset( $languagestrings[$language] ) ) {
		return;
	}
/*
	if( !lang_language_exists( $language ) ) {
		return;
	}
*/
	/*
        $t_lang_dir = $p_dir;

	if( is_null( $t_lang_dir ) ) {
		$t_lang_dir = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR;
                require_once( $t_lang_dir . 'strings_' . $p_lang . '.txt' );
	} else {
		if( is_file( $t_lang_dir . 'strings_' . $p_lang . '.txt' ) ) {
			include_once( $t_lang_dir . 'strings_' . $p_lang . '.txt' );
		}
	}
        */
	$lang_dir = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR;
        require_once( $lang_dir . 'strings_' . $language . '.txt' );

	$t_vars = get_defined_vars();
	foreach( array_keys( $t_vars ) as $t_var ) {
		$t_lang_var = preg_replace( '/^s_/', '', $t_var );
//CHANGE - 
                $g_lang_strings[$language][$t_lang_var] = $$t_var;
	   //   echo $language.'-'.$t_lang_var.'-'.$$t_var.',   ';
		
                if( $t_lang_var != $t_var ) {
			$g_lang_strings[$p_lang][$t_lang_var] = $$t_var;
		}
		else if( 'MANTIS_ERROR' == $t_var ) {
			if( isset( $g_lang_strings[$p_lang][$t_lang_var] ) ) {
				foreach( $$t_var as $key => $val ) {
					$g_lang_strings[$p_lang][$t_lang_var][$key] = $val;
				}
			} else {
				$g_lang_strings[$p_lang][$t_lang_var] = $$t_var;
			}
		}
	}
//die(var_dump($languagestrings));
        
}

function lang_ensure_loaded( $language ) {
	global $languagestrings;

	if( !isset( $languagestrings[$language] ) ) {
		lang_load( $language );
	}
}


/**
 * Determine the preferred language
 * @return string
 */
function lang_get_default() {
	global $active_language;

	$t_lang = false;

	# Confirm that the user's language can be determined
	if( function_exists( 'auth_is_user_authenticated' ) && auth_is_user_authenticated() ) {
	  //$t_lang = user_pref_get_language( auth_get_current_user_id() );
          $t_lang = 'english';	  
	}

	# Otherwise fall back to default
	if( !$t_lang ) {
#		$t_lang = config_get_global( 'default_language' );
		$t_lang = 'english';
	}

	# Remember the language
	$active_language = $t_lang;

	return $t_lang;
}

/**
 * return value on top of the language stack
 * return default if stack is empty
 * @return string
 */
function lang_get_current() {
    $t_lang = lang_get_default();
    return $t_lang;
}

/**
 * Check the language entry, if found return true, otherwise return false.
 * @param string $p_string
 * @param string $p_lang
 * @return bool
 */
function lang_exists( $p_string, $p_lang ) {
	global $languagestrings;
      	return( isset( $$languagestrings[$p_lang] ) && isset( $$languagestrings[$p_lang][$p_string] ) );
}

/**
 * Retrieves an internationalized string
 * This function will return one of (in order of preference):
 *  1. The string in the current user's preferred language (if defined)
 *  2. The string in English
 */
function lang_get( $key, $language = null ) {
    return $key;
	global $languagestrings;
# $P_string » key
	# If no specific language is requested, we'll
	#  try to determine the language from the users
	#  preferences

	$_lang = $language;

	if( null === $_lang ) {
		$_lang = lang_get_current();
	}
	// Now we'll make sure that the requested language is loaded
	lang_ensure_loaded( $_lang );

        if( lang_exists( $key, $_lang ) ) {
		return $languagestrings[$_lang][$key];
	} else {
/*		$t_plugin_current = plugin_get_current();
		if( !is_null( $t_plugin_current ) ) {
			lang_load( $t_lang, config_get( 'plugin_path' ) . $t_plugin_current . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR );
			if( lang_exists( $p_string, $t_lang ) ) {
				return $g_lang_strings[$t_lang][$p_string];
			}
		}
*/
		if( $_lang == 'english' ) {
			//error_parameters( $p_string );
			//trigger_error( ERROR_LANG_STRING_NOT_FOUND, WARNING );
			return '';
		} else {

			# if string is not found in a language other than english, then retry using the english language.
			return lang_get( $p_string, 'english' );
		}
	}
}


class lang {
    static function init() {
        if(!($lang = new lang()))
            return null;

        return $lang;
    }

    function lang() {
        //
    }

    
}

?>