<div id="jsplaylist<?php echo absint( $first_video['playlist'] ); ?>" class="flowplayer-playlist flowplayer-playlist-<?php echo absint( $first_video['playlist'] ). ' ' . esc_attr( $first_video['playlist_options']['fp5-select-skin'] ) . ' ' . esc_attr( self::trim_implode( $first_video['classes'] ) ); ?>" style="<?php echo esc_attr( self::process_css_array( $first_video['style'] ) ); ?>" >
	<a class="fp-prev"><?php _e( '&lt; Prev', 'flowplayer5' ); ?></a>
	<a class="fp-next"><?php _e( 'Next &gt;', 'flowplayer5' ); ?></a>
</div>
<?php
do_action( 'fp5_below_playlist', $first_video['playlist'], $atts );

if ( 'fp6' === $first_video['fp_version'] ) {
	require( 'playlist-script-v6.php' );
} else {
	require( 'playlist-script-v5.php' );
}
