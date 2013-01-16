<?php

/**
 * These functions control the display of each page
 *
 * This is the call order of these functions, should you need to figure out
 * which to modify or which to leave out.
 *
 * html_page_top1
 * 	html_begin
 * 	html_head_begin
 * 	html_css
 * 	html_content_type
 * 	html_rss_link
 * 	(html_meta_redirect)
 * 	html_title
 * html_page_top2
 * 	html_page_top2a
 * 	html_head_end
 * 	html_body_begin
 * 	html_header
 * 	html_top_banner
 * 	html_login_info
 * 	(print_project_menu_bar)
 * 	print_menu
 *
 * ...Page content here...
 *
 * html_page_bottom1
 * 	(print_menu)
 * 	html_page_bottom1a
 * 	html_bottom_banner
 * 	html_footer
 * 	html_body_end
 * html_end
 *
*/

#require_once( 'current_user_api.php' );
#require_once( 'string_api.php' );
#require_once( 'bug_api.php' );
#require_once( 'project_api.php' );
#require_once( 'helper_api.php' );
#require_once( 'authentication_api.php' );
#require_once( 'user_api.php' );
#require_once( 'php_api.php' );

# flag for error handler to skip header menus
$g_error_send_page_header = true;

/**
 * Prints a <script> tag to include a javascript file.
 * This includes either minimal or development file from /javascript depending on whether mantis is set for debug/production use
 * @param string $p_filename
 * @return null
 */
function html_javascript_link( $p_filename) {
	if( config_get_global( 'minimal_jscss' ) ) {
		echo '<script type="text/javascript" src="', helper_mantis_url( 'javascript/min/' . $p_filename ), '"></script>' . "\n";
	} else {
		echo '<script type="text/javascript" src="', helper_mantis_url( 'javascript/dev/' . $p_filename ), '"></script>' . "\n";
	}
}

/**
 * Defines the top of a HTML page
 * @param string $p_page_title html page title
 * @param string $p_redirect_url url to redirect to if necessary
 * @return null
 */
function html_page_top( $p_page_title = null, $p_redirect_url = null ) {
	html_begin();
	html_head_begin();
	html_css();
	html_content_type();
/*
  	include( config_get( 'meta_include_file' ) );
 
	$t_favicon_image = config_get( 'favicon_image' );
	if( !is_blank( $t_favicon_image ) ) {
		echo "\t", '<link rel="shortcut icon" href="', helper_mantis_url( $t_favicon_image ), '" type="image/x-icon" />', "\n";
	}

	// Advertise the availability of the browser search plug-ins.
	echo "\t", '<link rel="search" type="application/opensearchdescription+xml" title="MantisBT: Text Search" href="' . string_sanitize_url( 'browser_search_plugin.php?type=text', true) . '" />';
	echo "\t", '<link rel="search" type="application/opensearchdescription+xml" title="MantisBT: Issue Id" href="' . string_sanitize_url( 'browser_search_plugin.php?type=id', true) . '" />';
*/
	html_title( $p_page_title );
	html_head_javascript();

        if ( $p_redirect_url !== null ) {
		html_meta_redirect( $p_redirect_url );
	}

        global $g_error_send_page_header;

	html_head_end();
	html_body_begin();
	$g_error_send_page_header = false;
	html_header();
	html_top_banner();
/*
	if( !db_is_connected() ) {
		return;
	}

	if( auth_is_user_authenticated() ) {
		html_login_info();

		if( ON == config_get( 'show_project_menu_bar' ) ) {
			print_project_menu_bar();
			echo '<br />';
		}
	}
*/	print_menu();

#	event_signal( 'EVENT_LAYOUT_CONTENT_BEGIN' );
}

/**
 * Print the part of the page that comes below the page content
 * $p_file should always be the __FILE__ variable. This is passed to show source
 * @param string $p_file should always be the __FILE__ variable. This is passed to show source
 * @return null
 */
function html_page_bottom( $p_file = null ) {
	if( !db_is_connected() ) {
		return;
	}

	event_signal( 'EVENT_LAYOUT_CONTENT_END' );

	if( config_get( 'show_footer_menu' ) ) {
		echo '<br />';
		print_menu();
	}

        if( null === $p_file ) {
		$p_file = basename( $_SERVER['SCRIPT_NAME'] );
	}

	html_bottom_banner();
	html_footer();
	html_body_end();
	html_end();
}

/**
 * (1) Print the document type and the opening <html> tag
 * @return null
 */
function html_begin() {
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">', "\n";
	echo '<html>', "\n";
}

/**
 * (2) Begin the <head> section
 * @return null
 */
function html_head_begin() {
	echo '<head>', "\n";
}

/**
 * (3) Print the content-type
 * @return null
 */
function html_content_type() {
	echo "\t", '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />', "\n";
}

/**
 * (4) Print the window title
 * @param string $p_page_title window title
 * @return null
 */
function html_title( $p_page_title = null ) {
	$t_page_title = $p_page_title; //string_html_specialchars( $p_page_title );
	$t_title = 'Window title';//string_html_specialchars( config_get( 'window_title' ) );
	echo "\t", '<title>';
	if( empty( $t_page_title ) ) {
		echo $t_title;
	} else {
		if( empty( $t_title ) ) {
			echo $t_page_title;
		} else {
			echo $t_page_title . ' - ' . $t_title;
		}
	}
	echo '</title>', "\n";
}

/**
 * (5) Print the link to include the css file
 * @return null
 */
function html_css() {
/*	$t_css_url = config_get( 'css_include_file' );
	echo "\t", '<link rel="stylesheet" type="text/css" href="', string_sanitize_url( helper_mantis_url( $t_css_url ), true ), '" />', "\n";
*/
	# Add right-to-left css if needed
	if( lang_get( 'directionality' ) == 'rtl' ) {
		$t_css_rtl_url = config_get( 'css_rtl_include_file' );
		echo "\t", '<link rel="stylesheet" type="text/css" href="', string_sanitize_url( helper_mantis_url( $t_css_rtl_url ), true ), '" />', "\n";
	}

	# fix for NS 4.x css
	echo "\t", '<script type="text/javascript"><!--', "\n";
	echo "\t\t", 'if(document.layers) {document.write("<style>td{padding:0px;}<\/style>")}', "\n";
	echo "\t", '// --></script>', "\n";
}

/**
 * (6) Print an HTML meta tag to redirect to another page
 * This function is optional and may be called by pages that need a redirect.
 * $p_time is the number of seconds to wait before redirecting.
 * If we have handled any errors on this page and the 'stop_on_errors' config
 *  option is turned on, return false and don't redirect.
 *
 * @param string $p_url The page to redirect: has to be a relative path
 * @param integer $p_time seconds to wait for before redirecting
 * @param boolean $p_sanitize apply string_sanitize_url to passed url
 * @return boolean
 */
function html_meta_redirect( $p_url, $p_time = null, $p_sanitize = true ) {
	if( ON == config_get_global( 'stop_on_errors' ) && error_handled() ) {
		return false;
	}

	if( null === $p_time ) {
		$p_time = current_user_get_pref( 'redirect_delay' );
	}

	$t_url = config_get( 'path' );
	if( $p_sanitize ) {
		$t_url .= string_sanitize_url( $p_url );
	} else {
		$t_url .= $p_url;
	}

	$t_url = htmlspecialchars( $t_url );

	echo "\t<meta http-equiv=\"Refresh\" content=\"$p_time;URL=$t_url\" />\n";

	return true;
}

/**
 * (6a) Javascript...
 * @return null
 */
function html_head_javascript() {
	/*
        if( ON == config_get( 'use_javascript' ) ) {
         
		html_javascript_link( 'common.js' );
		echo '<script type="text/javascript">var loading_lang = "' . lang_get( 'loading' ) . '";</script>';
		html_javascript_link( 'ajax.js' );

		global $g_enable_projax;

		if( $g_enable_projax ) {
			html_javascript_link( 'projax/prototype.js' );
			html_javascript_link( 'projax/scriptaculous.js' );
		}
	}
        */
        
}

/**
 * (7) End the <head> section
 * @return null
 */
function html_head_end() {
	//event_signal( 'EVENT_LAYOUT_RESOURCES' );

	echo '</head>', "\n";
}

/**
 * (8) Begin the <body> section
 * @return null
 */
function html_body_begin() {
	echo '<body>', "\n";

	//event_signal( 'EVENT_LAYOUT_BODY_BEGIN' );
}

/**
 * (9) Print the title displayed at the top of the page
 * @return null
 */
function html_header() {
	$t_title = 'page title';// config_get( 'page_title' );
	if( true ) {
	#if( !is_blank( $t_title ) ) {
	#	echo '<div class="center"><span class="pagetitle">', string_display( $t_title ), '</span></div>', "\n";
	}
}

/**
 * (10) Print a user-defined banner at the top of the page if there is one.
 * @return null
 */
function html_top_banner() {
/*	$t_page = '';//config_get( 'top_include_page' );
	$t_logo_image = '';//config_get( 'logo_image' );
	$t_logo_url = '';//config_get( 'logo_url' );

	if( is_blank( $t_logo_image ) ) {
		$t_show_logo = false;
	} else {
		$t_show_logo = true;
		if( is_blank( $t_logo_url ) ) {
			$t_show_url = false;
		} else {
			$t_show_url = true;
		}
	}

	if( !is_blank( $t_page ) && file_exists( $t_page ) && !is_dir( $t_page ) ) {
		include( $t_page );
	} else if( $t_show_logo ) {
		$t_align = should_center_logo() ? 'center' : 'left';

		echo '<div align="', $t_align, '">';
		if( $t_show_url ) {
			echo '<a href="', config_get( 'logo_url' ), '">';
		}
		$t_alternate_text = string_html_specialchars( config_get( 'window_title' ) );
		echo '<img border="0" alt="', $t_alternate_text, '" src="' . helper_mantis_url( $t_logo_image ) . '" />';
		if( $t_show_url ) {
			echo '</a>';
		}
		echo '</div>';
	}
*/
	#event_signal( 'EVENT_LAYOUT_PAGE_HEADER' );
}

/**
 * (11) Print the user's account information
 * Also print the select box where users can switch projects
 * @return null
 */
function html_login_info() {
	$t_username = current_user_get_field( 'username' );
	$t_access_level = get_enum_element( 'access_levels', current_user_get_access_level() );
	$t_now = date( config_get( 'complete_date_format' ) );
	$t_realname = current_user_get_field( 'realname' );

	echo '<table class="hide">';
	echo '<tr>';
	echo '<td class="login-info-left">';
	if( current_user_is_anonymous() ) {
		$t_return_page = $_SERVER['SCRIPT_NAME'];
		if( isset( $_SERVER['QUERY_STRING'] ) ) {
			$t_return_page .= '?' . $_SERVER['QUERY_STRING'];
		}

		$t_return_page = string_url( $t_return_page );
		echo lang_get( 'anonymous' ) . ' | <a href="' . helper_mantis_url( 'login_page.php?return=' . $t_return_page ) . '">' . lang_get( 'login_link' ) . '</a>';
		if( config_get_global( 'allow_signup' ) == ON ) {
			echo ' | <a href="' . helper_mantis_url( 'signup_page.php' ) . '">' . lang_get( 'signup_link' ) . '</a>';
		}
	} else {
		echo lang_get( 'logged_in_as' ), ": <span class=\"italic\">", string_html_specialchars( $t_username ), "</span> <span class=\"small\">";
		echo is_blank( $t_realname ) ? "($t_access_level)" : "(" . string_html_specialchars( $t_realname ) . " - $t_access_level)";
		echo "</span>";
	}
	echo '</td>';
	echo '<td class="login-info-middle">';
	echo "<span class=\"italic\">$t_now</span>";
	echo '</td>';
	echo '<td class="login-info-right">';

	# Project Selector hidden if only one project visisble to user
	$t_show_project_selector = true;
	$t_project_ids = current_user_get_accessible_projects();
	if( count( $t_project_ids ) == 1 ) {
		$t_project_id = (int) $t_project_ids[0];
		if( count( current_user_get_accessible_subprojects( $t_project_id ) ) == 0 ) {
			$t_show_project_selector = false;
		}
	}

	if( $t_show_project_selector ) {
		echo '<form method="post" name="form_set_project" action="' . helper_mantis_url( 'set_project.php' ) . '">';
		# CSRF protection not required here - form does not result in modifications

		echo lang_get( 'email_project' ), ': ';
		if( ON == config_get( 'show_extended_project_browser' ) ) {
			print_extended_project_browser( helper_get_current_project_trace() );
		} else {
			if( ON == config_get( 'use_javascript' ) ) {
				echo '<select name="project_id" class="small" onchange="document.forms.form_set_project.submit();">';
			} else {
				echo '<select name="project_id" class="small">';
			}
			print_project_option_list( join( ';', helper_get_current_project_trace() ), true, null, true );
			echo '</select> ';
		}
		echo '<input type="submit" class="button-small" value="' . lang_get( 'switch' ) . '" />';
		echo '</form>';
	} else {
		# User has only one project, set it as both current and default
		if( ALL_PROJECTS == helper_get_current_project() ) {
			helper_set_current_project( $t_project_id );

			if ( !current_user_is_protected() ) {
				current_user_set_default_project( $t_project_id );
			}

			# Force reload of current page
			$t_redirect_url = str_replace( config_get( 'short_path' ), '', $_SERVER['REQUEST_URI'] );
			html_meta_redirect( $t_redirect_url, 0, false );
		}
	}

	if( OFF != config_get( 'rss_enabled' ) ) {

		# Link to RSS issues feed for the selected project, including authentication details.
		echo '<a href="' . htmlspecialchars( rss_get_issues_feed_url() ) . '">';
		echo '<img src="' . helper_mantis_url( 'images/rss.png' ) . '" alt="' . lang_get( 'rss' ) . '" style="border-style: none; margin: 5px; vertical-align: middle;" />';
		echo '</a>';
	}

	echo '</td>';
	echo '</tr>';
	echo '</table>';
}

/**
 * (12) Print a user-defined banner at the bottom of the page if there is one.
 * @return null
 */
function html_bottom_banner() {
	$t_page = config_get( 'bottom_include_page' );

	if( !is_blank( $t_page ) && file_exists( $t_page ) && !is_dir( $t_page ) ) {
		include( $t_page );
	}
}

/**
 * (13) Print the page footer information
 * @param string $p_file
 * @return null
 */
function html_footer( $p_file = null ) {
	global $g_queries_array, $g_request_time;

	# If a user is logged in, update their last visit time.
	# We do this at the end of the page so that:
	#  1) we can display the user's last visit time on a page before updating it
	#  2) we don't invalidate the user cache immediately after fetching it
	#  3) don't do this on the password verification or update page, as it causes the
	#    verification comparison to fail
	if ( auth_is_user_authenticated() && !current_user_is_anonymous() && !( is_page_name( 'verify.php' ) || is_page_name( 'account_update.php' ) ) ) {
		$t_user_id = auth_get_current_user_id();
		user_update_last_visit( $t_user_id );
	}

	echo "\t", '<br />', "\n";
	echo "\t", '<hr size="1" />', "\n";

	echo '<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr valign="top"><td>';
	if( ON == config_get( 'show_version' ) ) {
		$t_version_suffix = config_get_global( 'version_suffix' );
		echo "\t", '<span class="timer"><a href="http://www.mantisbt.org/" title="Free Web Based Bug Tracker">MantisBT ', MANTIS_VERSION, ( $t_version_suffix ? " $t_version_suffix" : '' ), '</a>', '[<a href="http://www.mantisbt.org/"  title="Free Web Based Bug Tracker" target="_blank">^</a>]</span>', "\n";
	}
	echo "\t", '<address>Copyright &copy; 2000 - 2012 MantisBT Group</address>', "\n";

	# only display webmaster email is current user is not the anonymous user
	if( !is_page_name( 'login_page.php' ) && auth_is_user_authenticated() && !current_user_is_anonymous() ) {
		echo "\t", '<address><a href="mailto:', config_get( 'webmaster_email' ), '">', config_get( 'webmaster_email' ), '</a></address>', "\n";
	}

	event_signal( 'EVENT_LAYOUT_PAGE_FOOTER' );

	# print timings
	if( ON == config_get( 'show_timer' ) ) {
		echo '<span class="italic">Time: ' . number_format( microtime(true) - $g_request_time, 4 ) . ' seconds.</span><br />';
		echo sprintf( lang_get( 'memory_usage_in_kb' ), number_format( memory_get_peak_usage() / 1024 ) ), '<br />';
	}

	# print db queries that were run
	if( helper_show_queries() ) {
		$t_count = count( $g_queries_array );
		echo "\t";
		echo sprintf( lang_get( 'total_queries_executed' ), $t_count );
		echo "<br />\n";

		if( ON == config_get( 'show_queries_list' ) ) {
			$t_unique_queries = 0;
			$t_shown_queries = array();
			for( $i = 0;$i < $t_count;$i++ ) {
				if( !in_array( $g_queries_array[$i][0], $t_shown_queries ) ) {
					$t_unique_queries++;
					$g_queries_array[$i][3] = false;
					array_push( $t_shown_queries, $g_queries_array[$i][0] );
				} else {
					$g_queries_array[$i][3] = true;
				}
			}

			echo "\t";
			echo sprintf( lang_get( 'unique_queries_executed' ), $t_unique_queries );
			echo "\t", '<table>', "\n";
			$t_total = 0;
			for( $i = 0;$i < $t_count;$i++ ) {
				$t_time = $g_queries_array[$i][1];
				$t_caller = $g_queries_array[$i][2];
				$t_total += $t_time;
				$t_style_tag = '';
				if( true == $g_queries_array[$i][3] ) {
					$t_style_tag = ' style="color: red;"';
				}
				echo "\t", '<tr valign="top"><td', $t_style_tag, '>', ( $i + 1 ), '</td>';
				echo '<td', $t_style_tag, '>', $t_time, '</td>';
				echo '<td', $t_style_tag, '><span style="color: gray;">', $t_caller, '</span><br />', string_html_specialchars( $g_queries_array[$i][0] ), '</td></tr>', "\n";
			}

			# @@@ Note sure if we should localize them given that they are debug info.  Will add if requested by users.
			echo "\t", '<tr><td></td><td>', $t_total, '</td><td>SQL Queries Total Time</td></tr>', "\n";
			echo "\t", '<tr><td></td><td>', round( microtime(true) - $g_request_time, 4 ), '</td><td>Page Request Total Time</td></tr>', "\n";
			echo "\t", '</table>', "\n";
		}
	}

	echo '</td><td>', "\n\t";

	# We don't have a button anymore, so for now we will only show the resized version of the logo when not on login page.
	if ( !is_page_name( 'login_page' ) ) {
		echo '<div align="right">';
		echo '<a href="http://www.mantisbt.org" title="Free Web Based Bug Tracker"><img src="' . helper_mantis_url( 'images/mantis_logo.png' ) . '" width="145" height="50" alt="Powered by Mantis Bugtracker" border="0" /></a>';
		echo '</div>', "\n";
	}

	echo '</td></tr></table>', "\n";
}

/**
 * (14) End the <body> section
 * @return null
 */
function html_body_end() {
	event_signal( 'EVENT_LAYOUT_BODY_END' );

	echo '</body>', "\n";
}

/**
 * (15) Print the closing <html> tag
 * @return null
 */
function html_end() {
	echo '</html>', "\n";
}

/**
 * Prepare an array of additional menu options from a config variable
 * @param string $p_config config name
 * @return array
 */
function prepare_custom_menu_options( $p_config ) {
	$t_custom_menu_options = config_get( $p_config );
	$t_options = array();

	foreach( $t_custom_menu_options as $t_custom_option ) {
		$t_access_level = $t_custom_option[1];
		if( access_has_project_level( $t_access_level ) ) {
			$t_caption = string_html_specialchars( lang_get_defaulted( $t_custom_option[0] ) );
			$t_link = string_attribute( $t_custom_option[2] );
			$t_options[] = "<a href=\"$t_link\">$t_caption</a>";
		}
	}

	return $t_options;
}

/**
 * Print the main menu
 * @return null
 */
function print_menu() {
/*	if( auth_is_user_authenticated() ) {
		$t_protected = current_user_get_field( 'protected' );
		$t_current_project = helper_get_current_project();

		echo '<table class="width100" cellspacing="0">';
		echo '<tr>';
		echo '<td class="menu">';
		$t_menu_options = array();

		# Main Page
		$t_menu_options[] = '<a href="' . helper_mantis_url( 'main_page.php' ) . '">' . lang_get( 'main_link' ) . '</a>';

		# Plugin / Event added options
		$t_event_menu_options = event_signal( 'EVENT_MENU_MAIN_FRONT' );
		foreach( $t_event_menu_options as $t_plugin => $t_plugin_menu_options ) {
			foreach( $t_plugin_menu_options as $t_callback => $t_callback_menu_options ) {
				if( is_array( $t_callback_menu_options ) ) {
					$t_menu_options = array_merge( $t_menu_options, $t_callback_menu_options );
				} else {
					if ( !is_null( $t_callback_menu_options ) ) {
						$t_menu_options[] = $t_callback_menu_options;
					}
				}
			}
		}

		# My View
		$t_menu_options[] = '<a href="' . helper_mantis_url( 'my_view_page.php">' ) . lang_get( 'my_view_link' ) . '</a>';

		# View Bugs
		$t_menu_options[] = '<a href="' . helper_mantis_url( 'view_all_bug_page.php">' ) . lang_get( 'view_bugs_link' ) . '</a>';

		# Report Bugs
		if( access_has_project_level( config_get( 'report_bug_threshold' ) ) ) {
			$t_menu_options[] = string_get_bug_report_link();
		}

		# Changelog Page
		if( access_has_project_level( config_get( 'view_changelog_threshold' ) ) ) {
			$t_menu_options[] = '<a href="' . helper_mantis_url( 'changelog_page.php">' ) . lang_get( 'changelog_link' ) . '</a>';
		}

		# Roadmap Page
		if( access_has_project_level( config_get( 'roadmap_view_threshold' ) ) ) {
			$t_menu_options[] = '<a href="' . helper_mantis_url( 'roadmap_page.php">' ) . lang_get( 'roadmap_link' ) . '</a>';
		}

		# Summary Page
		if( access_has_project_level( config_get( 'view_summary_threshold' ) ) ) {
			$t_menu_options[] = '<a href="' . helper_mantis_url( 'summary_page.php">' ) . lang_get( 'summary_link' ) . '</a>';
		}

		# Project Documentation Page
		if( ON == config_get( 'enable_project_documentation' ) ) {
			$t_menu_options[] = '<a href="' . helper_mantis_url( 'proj_doc_page.php">' ) . lang_get( 'docs_link' ) . '</a>';
		}

		# Project Wiki
		if( config_get_global( 'wiki_enable' ) == ON ) {
			$t_menu_options[] = '<a href="' . helper_mantis_url( 'wiki.php?type=project&amp;id=' ) . $t_current_project . '">' . lang_get( 'wiki' ) . '</a>';
		}

		# Plugin / Event added options
		$t_event_menu_options = event_signal( 'EVENT_MENU_MAIN' );
		foreach( $t_event_menu_options as $t_plugin => $t_plugin_menu_options ) {
			foreach( $t_plugin_menu_options as $t_callback => $t_callback_menu_options ) {
				if( is_array( $t_callback_menu_options ) ) {
					$t_menu_options = array_merge( $t_menu_options, $t_callback_menu_options );
				} else {
					if ( !is_null( $t_callback_menu_options ) ) {
						$t_menu_options[] = $t_callback_menu_options;
					}
				}
			}
		}

		# Manage Users (admins) or Manage Project (managers) or Manage Custom Fields
		if( access_has_global_level( config_get( 'manage_site_threshold' ) ) ) {
			$t_link = helper_mantis_url( 'manage_overview_page.php' );
			$t_menu_options[] = "<a href=\"$t_link\">" . lang_get( 'manage_link' ) . '</a>';
		} else {
			$t_show_access = min( config_get( 'manage_user_threshold' ), config_get( 'manage_project_threshold' ), config_get( 'manage_custom_fields_threshold' ) );
			if( access_has_global_level( $t_show_access ) || access_has_any_project( $t_show_access ) ) {
				$t_current_project = helper_get_current_project();
				if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
					$t_link = helper_mantis_url( 'manage_user_page.php' );
				} else {
					if( access_has_project_level( config_get( 'manage_project_threshold' ), $t_current_project ) && ( $t_current_project <> ALL_PROJECTS ) ) {
						$t_link = helper_mantis_url( 'manage_proj_edit_page.php?project_id=' ) . $t_current_project;
					} else {
						$t_link = helper_mantis_url( 'manage_proj_page.php' );
					}
				}
				$t_menu_options[] = "<a href=\"$t_link\">" . lang_get( 'manage_link' ) . '</a>';
			}
		}

		# News Page
		if ( news_is_enabled() && access_has_project_level( config_get( 'manage_news_threshold' ) ) ) {

			# Admin can edit news for All Projects (site-wide)
			if( ALL_PROJECTS != helper_get_current_project() || current_user_is_administrator() ) {
				$t_menu_options[] = '<a href="' . helper_mantis_url( 'news_menu_page.php">' ) . lang_get( 'edit_news_link' ) . '</a>';
			} else {
				$t_menu_options[] = '<a href="' . helper_mantis_url( 'login_select_proj_page.php">' ) . lang_get( 'edit_news_link' ) . '</a>';
			}
		}

		# Account Page (only show accounts that are NOT protected)
		if( OFF == $t_protected ) {
			$t_menu_options[] = '<a href="' . helper_mantis_url( 'account_page.php">' ) . lang_get( 'account_link' ) . '</a>';
		}

		# Add custom options
		$t_custom_options = prepare_custom_menu_options( 'main_menu_custom_options' );
		$t_menu_options = array_merge( $t_menu_options, $t_custom_options );

		# Time Tracking / Billing
		if( config_get( 'time_tracking_enabled' ) && access_has_global_level( config_get( 'time_tracking_reporting_threshold' ) ) ) {
			$t_menu_options[] = '<a href="' . helper_mantis_url( 'billing_page.php">' ) . lang_get( 'time_tracking_billing_link' ) . '</a>';
		}

		# Logout (no if anonymously logged in)
		if( !current_user_is_anonymous() ) {
			$t_menu_options[] = '<a href="' . helper_mantis_url( 'logout_page.php">' ) . lang_get( 'logout_link' ) . '</a>';
		}
		echo implode( $t_menu_options, ' | ' );
		echo '</td>';
		echo '<td class="menu right nowrap">';
		echo '<form method="post" action="' . helper_mantis_url( 'jump_to_bug.php">' );
		# CSRF protection not required here - form does not result in modifications

		if( ON == config_get( 'use_javascript' ) ) {
			$t_bug_label = lang_get( 'issue_id' );
			echo "<input type=\"text\" name=\"bug_id\" size=\"10\" class=\"small\" value=\"$t_bug_label\" onfocus=\"if (this.value == '$t_bug_label') this.value = ''\" onblur=\"if (this.value == '') this.value = '$t_bug_label'\" />&#160;";
		} else {
			echo "<input type=\"text\" name=\"bug_id\" size=\"10\" class=\"small\" />&#160;";
		}

		echo '<input type="submit" class="button-small" value="' . lang_get( 'jump' ) . '" />&#160;';
		echo '</form>';
		echo '</td>';
		echo '</tr>';
		echo '</table>';
	}
*/

}

/**
 * Print the menu bar with a list of projects to which the user has access
 * @return null
 */
function print_project_menu_bar() {
	$t_project_ids = current_user_get_accessible_projects();

	echo '<table class="width100" cellspacing="0">';
	echo '<tr>';
	echo '<td class="menu">';
	echo '<a href="' . helper_mantis_url( 'set_project.php?project_id=' . ALL_PROJECTS ) . '">' . lang_get( 'all_projects' ) . '</a>';

	foreach( $t_project_ids as $t_id ) {
		echo ' | <a href="' . helper_mantis_url( 'set_project.php?project_id=' . $t_id ) . '">' . string_html_specialchars( project_get_field( $t_id, 'name' ) ) . '</a>';
		print_subproject_menu_bar( $t_id, $t_id . ';' );
	}

	echo '</td>';
	echo '</tr>';
	echo '</table>';
}

