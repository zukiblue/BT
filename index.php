<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
#  BT - 
#
#
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 

require_once 'core.php';

echo "The Index";

#require_once( 'core.php' );

#require_once( 'current_user_api.php' );
#require_once( 'news_api.php' );
#require_once( 'date_api.php' );
#require_once( 'print_api.php' );
#require_once( 'rss_api.php' );

#access_ensure_project_level( VIEWER );

#$f_offset = gpc_get_int( 'offset', 0 );

#$t_project_id = helper_get_current_project();

#$t_rss_enabled = config_get( 'rss_enabled' );
/*
if ( OFF != $t_rss_enabled && news_is_enabled() ) {
        $t_rss_link = rss_get_news_feed_url( $t_project_id );
        html_set_rss_link( $t_rss_link );
}
*/
html_page_top( lang_get( 'main_link' ) );
