<?php

    require_once( 'core.php' );
    require_once( 'current_user_api.php' );
	
   // access_ensure_project_level( VIEWER );

    
    html_page_top( lang_get( 'main_title' ) );
    
    
   /* if ( !current_user_is_anonymous() ) {
		$t_current_user_id = auth_get_current_user_id();
		$t_hide_status = config_get( 'bug_resolved_status_threshold' );
		echo '<div class="quick-summary-left">';
		echo lang_get( 'open_and_assigned_to_me' ) . ': ';
		print_link( "view_all_set.php?type=1&handler_id=$t_current_user_id&hide_status=$t_hide_status", current_user_get_assigned_open_bug_count(), false, 'subtle' );
		echo '</div>';

		echo '<div class="quick-summary-right">';
		echo lang_get( 'open_and_reported_to_me' ) . ': ';
		print_link( "view_all_set.php?type=1&reporter_id=$t_current_user_id&hide_status=$t_hide_status", current_user_get_reported_open_bug_count(), false, 'subtle' );
		echo '</div>';

		echo '<div class="quick-summary-left">';
		echo lang_get( 'last_visit' ) . ': ';
		echo date( config_get( 'normal_date_format' ), current_user_get_field( 'last_visit' ) );
		echo '</div>';
	}
     else*/
        echo 'anonymous';

	echo '<br />';
	echo '<br />';

    
    html_page_bottom();

    ?>
