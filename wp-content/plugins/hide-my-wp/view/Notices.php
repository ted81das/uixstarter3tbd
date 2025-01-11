<?php defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );
if ( ! isset( $message ) || ! isset( $type ) || ! isset( $ignore ) ) {
	return;
} ?>
<div class="hmwp_notice <?php echo esc_attr( $type ) ?>">
	<?php echo wp_kses_post( $message ) ?>

	<?php if ( $type == 'notice' && $ignore ) { ?>
        <a href="<?php echo esc_url( add_query_arg( array( 'hmwp_nonce' => wp_create_nonce( 'hmwp_ignoreerror' ), 'action' => 'hmwp_ignoreerror', 'hash'       => strlen( $message ) ) ) ) ?>" style="float: right; color: gray; text-decoration: underline; font-size: 0.8rem;"><?php echo esc_html__( 'ignore alert', 'hide-my-wp' ) ?></a>
	<?php } ?>
</div>
