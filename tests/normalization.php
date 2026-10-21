<?php
/**
 * Standalone behavior checks. Run: php tests/normalization.php
 */

define( 'ABSPATH', __DIR__ . '/' );

$registered_hooks = array();

function add_action( $hook, $callback ) {
	$GLOBALS['registered_hooks'][ $hook ] = $callback;
}

function add_filter( $hook, $callback ) {
	$GLOBALS['registered_hooks'][ $hook ] = $callback;
}

require dirname( __DIR__ ) . '/juliepr-remove-nbsp.php';

function check_same( $expected, $actual, $label ) {
	if ( $expected !== $actual ) {
		fwrite( STDERR, "FAIL: $label\n" );
		exit( 1 );
	}
}

check_same( 'juliepr_remove_nbsp_filter_post_data', $registered_hooks['wp_insert_post_data'], 'Save hook registration' );
check_same( 'juliepr_remove_nbsp_load_textdomain', $registered_hooks['init'], 'Translation hook registration' );

$input    = "A\xC2\xA0B&nbsp;C&#160;D&#xA0;E&#0160;F&#x00a0;G&NBSP;H";
$expected = 'A B C D E F G H';

foreach ( array( 'post', 'page' ) as $type ) {
	$data = array(
		'post_type'    => $type,
		'post_title'   => $input,
		'post_content' => $input,
		'post_excerpt' => $input,
		'post_name'    => 'keep&nbsp;slug',
		'guid'         => 'https://example.org/?a=1&b=2',
	);
	$want = array_merge( $data, array( 'post_title' => $expected, 'post_content' => $expected, 'post_excerpt' => $expected ) );
	$got  = juliepr_remove_nbsp_filter_post_data( $data );
	check_same( $want, $got, "$type fields and untouched properties" );
	check_same( $got, juliepr_remove_nbsp_filter_post_data( $got ), "$type idempotence" );
}

foreach ( array( 'attachment', 'revision', 'product' ) as $type ) {
	$data = array( 'post_type' => $type, 'post_content' => $input );
	check_same( $data, juliepr_remove_nbsp_filter_post_data( $data ), "$type exclusion" );
}

$unchanged = array(
	'post_type'    => 'post',
	'post_content' => "<p class=\\\"demo\\\">It\\'s &amp; &lt; &amp;nbsp; &#161; &#xA1; nbsp a  b\n\tc\xE2\x80\xAFd</p>",
);
check_same( $unchanged, juliepr_remove_nbsp_filter_post_data( $unchanged ), 'Slashes, markup, other entities, and whitespace' );
check_same( array(), juliepr_remove_nbsp_filter_post_data( array() ), 'Missing post type' );
check_same( array( 'post_type' => 'page' ), juliepr_remove_nbsp_filter_post_data( array( 'post_type' => 'page' ) ), 'Missing fields' );

$markup = array( 'post_type' => 'post', 'post_content' => '<p title="a&nbsp;b">a&nbsp;&nbsp;b</p>' );
check_same( '<p title="a b">a  b</p>', juliepr_remove_nbsp_filter_post_data( $markup )['post_content'], 'Whole-field replacement and repeated spaces' );

$russian = require dirname( __DIR__ ) . '/languages/juliepr-remove-nbsp-ru_RU.l10n.php';
check_same( 'ru_RU', $russian['language'], 'Russian catalog locale' );
check_same( 'Juliepr — замена неразрывных пробелов', $russian['messages']['Juliepr Remove NBSP'], 'Russian plugin name' );

echo "All behavior checks passed.\n";
