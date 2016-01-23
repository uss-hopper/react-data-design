<?php
/**
 * Exception used for custom handling of type hinting errors
 *
 * Named after Java's venerable ClassCastException, this exception will be thrown when a type hint is invalid
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @see http://docs.oracle.com/javase/8/docs/api/java/lang/ClassCastException.html Java's ClassCastException
 * @see http://php.net/manual/en/language.oop5.typehinting.php PHP Type Hinting
 **/
class ClassCastException extends ErrorException {}

/**
 * registers the throwing of a ClassCastException when type hinting fails
 *
 * @param int $errorNumber error code
 * @param string $message error message
 * @param string $file file the error was triggered in
 * @param int $line line number the error was triggered on
 * @return bool false when a ClassCastException isn't needed (and we revert to PHP's default error handling)
 * @throws ClassCastException when a ClassCastException is needed
 **/
function registerClassCastException($errorNumber, $message, $file, $line) {
	// type hinting is always an E_RECOVERABLE_ERROR
	if($errorNumber === E_RECOVERABLE_ERROR) {
		throw(new ClassCastException($message, $errorNumber, 1, $file, $line));
	}
	// return false to defer to PHP's default error handling
	return(false);
}
set_error_handler("registerClassCastException");
?>