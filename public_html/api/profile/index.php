<?php


require_once( dirname( __DIR__, 3 ) . "/vendor/autoload.php" );
require_once( dirname( __DIR__, 3 ) . "/php/classes/autoload.php" );
require_once( dirname( __DIR__, 3 ) . "/php/lib/xsrf.php" );
require_once( "/etc/apache2/capstone-mysql/encrypted-config.php" );

use Edu\Cnm\Dmcdonald21\DataDesign\ {
	Tweet,
	Profile
};

/**
 * API for Tweet
 *
 * @author Gkephart
 * @version 1.0
 */

//verify the session, if it is not active start it
if ( session_status() !== PHP_SESSION_ACTIVE ) {
	session_start();
}
//prepare an empty reply
$reply         = new stdClass();
$reply->status = 200;
$reply->data   = null;

try {

} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch( TypeError $typeError ) {
	$reply->status  = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
header( "Content-type: application/json" );
if ( $reply->data === null ) {
	unset( $reply->data );
}
// encode and return reply to front end caller
echo json_encode( $reply );
