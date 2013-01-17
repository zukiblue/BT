<?php

function html_begin($pagetitle) {
    html_open();
    html_css();
    html_javascript();
    html_content_type();
    html_title($pagetitle);

    html_header();
    html_top_banner();
    html_login_info();
    print_menu();
}
 /*
 * ...Page content here...
 */
function html_end() {
    html_footer();
    html_close();
}

function html_open() {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">', "\n";
    echo '<html>', "\n";
    echo '<head>', "\n";
}

function html_css() {
   echo '<link type="text/css" href="./css/start/jquery-ui-1.9.2.custom.css" rel="stylesheet" />';
echo '
    ';        
#    <link rel="stylesheet" href="./css/scp.css" media="screen">
#<link type="text/css" rel="stylesheet" href="../css/font-awesome.css">
    #    <link rel="stylesheet" href="./css/typeahead.css" media="screen">
#    <link type="text/css" rel="stylesheet" href="./css/dropdown.css">
}

function html_javascript() {
//    html_javascript_link( 'common.js' );
//		echo '<script type="text/javascript">var loading_lang = "' . lang_get( 'loading' ) . '";</script>';
//    html_javascript_link( 'ajax.js' );
    html_javascript_link('jquery-1.8.3.js');
    #html_javascript_link('jquery-ui-1.8.18.custom.min.js');
    html_javascript_link('jquery-ui-1.9.2.custom.js');
    
    echo ' <script>
$(function() {
$( "#tabs" ).tabs();
$( "#menu" ).menu();
});
</script>
';

#    <script type="text/javascript" src="./js/scp.js"></script>
 #  <script type="text/javascript" src="./js/jquery.multifile.js"></script>
 #    <script type="text/javascript" src="./js/tips.js"></script>
#    <script type="text/javascript" src="./js/nicEdit.js"></script>
#    <script type="text/javascript" src="./js/bootstrap-typeahead.js"></script>
#    <script type="text/javascript" src="./js/jquery.dropdown.js"></script>
}

function html_javascript_link( $p_filename) {
    echo '<script type="text/javascript" src="./js/', $p_filename , '"></script>' . "\n";
}

function html_content_type() {
    echo "\t", '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />', "\n";
}

function html_title( $pagetitle = null ) {
    $t_page_title = $pagetitle; //string_html_specialchars( $p_page_title );
    $t_title = getlang( 'window_title' );//string_html_specialchars( config_get( 'window_title' ) );
    
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
    echo '</head>', "\n";
    echo '<body>', "\n";
    echo '<div id="container">';    

}

function html_header() {    
	$t_title = 'page title';// config_get( 'page_title' );
	if( !is_blank( $t_title ) ) {
		echo '<div class="center"><span class="pagetitle">', $t_title, '</span></div>', "\n";
	}
}

function html_top_banner() {
	$t_page = getvar( 'top_include_page' );
	$t_logo_image = getvar( 'logo_image' );
	$t_logo_url = getvar( 'logo_url' );

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
		//$t_align = should_center_logo() ? 'center' : 'left';
                $t_align = 'center';

		echo '<div align="', $t_align, '">';
		if( $t_show_url ) {
			echo '<a href="', getvar( 'logo_url' ), '">';
		}
		//$t_alternate_text = string_html_specialchars( getvar( 'window_title' ) );
		//echo '<img border="0" alt="', $t_alternate_text, '" src="' . helper_mantis_url( $t_logo_image ) . '" />';
		echo '<img border="0" alt="', $t_alternate_text, '" src="' . $t_logo_image . '" />';
		if( $t_show_url ) {
			echo '</a>';
		}
		echo '</div>';
	}

	#event_signal( 'EVENT_LAYOUT_PAGE_HEADER' );
}


function html_login_info() {
	/*$t_username = current_user_get_field( 'username' );
	$t_access_level = get_enum_element( 'access_levels', current_user_get_access_level() );
	$t_now = date( getconfig( 'complete_date_format' ) );
	$t_realname = current_user_get_field( 'realname' );
*/
	echo '<table class="hide">';
	echo '<tr>';
	echo '<td class="login-info-left">';
	/*
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
         *
         */
	echo '</td>';
	echo '<td class="login-info-middle">';
	echo "<span class=\"italic\">$t_now</span>";
	echo '</td>';
	echo '<td class="login-info-right">';

	echo '</td>';
	echo '</tr>';
	echo '</table>';
}

function print_menu() {
    
  echo '
<div id="container">
<div id="container-menu">
<ul id="menu">
        <li class="inactive"><a href="logs.php">Dashboard</a><ul>
<li><a class="logs" href="logs.php" title="" >System&nbsp;Logs</a></li>
</ul>

</li>
<li class="active"><a href="settings.php">Settings</a>
    <ul id="sub_nav">
        <li><a class="preferences active" href="settings.php?t=system" title="" >System&nbsp;Preferences</a></li><li><a class="ticket-settings" href="settings.php?t=tickets" title="" >Tickets</a></li><li><a class="email-settings" href="settings.php?t=emails" title="" >Emails</a></li><li><a class="kb-settings" href="settings.php?t=kb" title="" >Knowledgebase</a></li><li><a class="email-autoresponders" href="settings.php?t=autoresp" title="" >Autoresponder</a></li><li><a class="alert-settings" href="settings.php?t=alerts" title="" >Alerts&nbsp;&amp;&nbsp;Notices</a></li>    </ul>
        </li>
<li class="inactive"><a href="helptopics.php">Manage</a><ul>
<li><a class="helpTopics" href="helptopics.php" title="" >Help&nbsp;Topics</a></li><li><a class="ticketFilters" href="filters.php" title="Ticket&nbsp;Filters" >Ticket&nbsp;Filters</a></li><li><a class="sla" href="slas.php" title="" >SLA&nbsp;Plans</a></li><li><a class="api" href="apikeys.php" title="" >API&nbsp;Keys</a></li>
</ul>

</li>
<li class="inactive"><a href="emails.php">Emails</a><ul>
<li><a class="emailSettings" href="emails.php" title="Email Addresses" >Emails</a></li><li><a class="emailDiagnostic" href="banlist.php" title="Banned&nbsp;Emails" >Banlist</a></li><li><a class="emailTemplates" href="templates.php" title="Email Templates" >Templates</a></li><li><a class="emailDiagnostic" href="emailtest.php" title="Email Diagnostic" >Diagnostic</a></li>
</ul>

</li>
<li class="inactive"><a href="staff.php">Staff</a><ul>
<li><a class="users" href="staff.php" title="" >Staff&nbsp;Members</a></li><li><a class="teams" href="teams.php" title="" >Teams</a></li><li><a class="groups" href="groups.php" title="" >Groups</a></li><li><a class="departments" href="departments.php" title="" >Departments</a></li>
</ul>

</li>
    </ul>

</div>
<div id="content">
        ';
}

function html_footer() {
        # div end : content
    echo '
       </div>
        <div id="footer">
    ';
        global $g_request_time;
	echo '<hr size="1">';
    
        echo '<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr valign="top"><td>';
	echo "\t", '<address>Copyright &copy; 2013 BT</address>', "\n";

	# only display webmaster email is current user is not the anonymous user
	
	# print timings
	if( ON == getvar( 'show_timer' ) ) {
		echo '<span class="italic">Time: ' . number_format( microtime(true) - $g_request_time, 4 ) . ' seconds.</span><br />';
		//echo sprintf( lang_get( 'memory_usage_in_kb' ), number_format( memory_get_peak_usage() / 1024 ) ), '<br />';
	}

	echo '</td><td>', "\n\t";

	# We don't have a button anymore, so for now we will only show the resized version of the logo when not on login page.
	/*if ( !is_page_name( 'login_page' ) ) {
		echo '<div align="right">';
		echo '<a href="" title=""><img src="' . 'images/logo.png' . '" width="145" height="50" alt="" border="0" /></a>';
		echo '</div>', "\n";
	}*/

	echo '</td></tr></table>', "\n";
}

function html_close() {
    # div end : container
    echo '
    </div>
<div id="overlay"></div>
<div id="loading">
    <h4>Please Wait!</h4>
    <p>Please wait... it will take a second!</p>
</div>
        ';    
	echo '</body>', "\n";
	echo '</html>', "\n";
}

