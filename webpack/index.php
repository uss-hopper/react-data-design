<?php
require_once dirname(__DIR__, 1) . "/php/lib/xsrf.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
	setXsrfCookie();

}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<base href="<?php echo dirname($_SERVER["PHP_SELF"]) . "/"; ?>" />

		<title>Twitter Data Design Example</title>
	</head>
	<body>
		<data-design-app>Loading&hellip;</data-design-app>
	</body>
</html>