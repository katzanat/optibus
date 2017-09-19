<?php
/** 
 * Handles AJAX requests
 */
require_once 'app.php';

header( 'Content-Type: application/json' );

$allowed_actions = lc_get_allowed_ajax_actions();

if ( empty( $_REQUEST['nonce'] ) || ! lc_validate_nonce( $_REQUEST['nonce'] ) ) {
	http_response_code(403);
	$response = array( 'status' => 'error', 'msg' => 'Request not authorized' );
} elseif ( empty( $_REQUEST['action'] ) || ! in_array( $_REQUEST['action'], $allowed_actions ) ) {
	http_response_code(400);
	$response = array( 'status' => 'error', 'msg' => 'Bad Request' );
} else {
	// Do Ajax Action
	if ( function_exists( $_REQUEST['action'] ) ) {
		$response = $_REQUEST['action']( $_REQUEST );
	} else {
		http_response_code(500);
		$response = array( 'status' => 'error', 'msg' => 'Internal Server Error' );
	}
}

echo json_encode( $response );die;