<?php
require_once("/var/www/vendor/autoload.php");
use phpseclib\Crypt\AES;


class Secrets {

	/**
	 * password used for decrypting and encrypting
	 * @var string $password
	 **/
	private $password = "change me please";

	/**
	 * path to where the .ini file lives on the server.
	 * @var string $filename
	 **/
	private $filePath = null;
	/**
	 * constructor method that sets the file path
	 *
	 * @var string $newFilePath path to the ini file holding the cipher text.
	 * @throws \InvalidArgumentException if an error happened
	 **/

	public function __construct(string $newFilePath) {
		try{
			$this->setFilePath($newFilePath);
		} catch(\InvalidArgumentException $exception) {
			throw new \InvalidArgumentException($exception->getMessage());
		}
	}

	/**
	 * mutator method for setting the file path to the ini file.
	 *
	 * @param string $filePath path to the ini file that contains the needed cipher text.
	 */
	public function setFilePath(string $filePath): void {
		$this->filePath = $filePath;
	}

	/**
	 * reads an encrypted configuration file and decrypts and parses the parameters
	 *
	 * @return array all the parameters parsed from the configuration file
	 * @throws InvalidArgumentException if parsing or decryption is unsuccessful
	 **/
	private function getSecrets() {

		// verify the file is readable
		if(is_readable($this->filePath) === false) {
			throw(new InvalidArgumentException("configuration file is not readable"));
		}

		// read the encrypted config file
		if(($cipherText = file_get_contents($this->filePath)) == false) {
			throw(new InvalidArgumentException("unable to read configuration file"));
		}

		$cipherTextArray = explode(".", $cipherText);

		if((count($cipherTextArray)) !== 3) {
			throw new InvalidArgumentException("cipher text could not be encrypted.");
		}

		$rawCipherText = $cipherTextArray[0];
		$iv = $cipherTextArray[1];
		$salt = $cipherTextArray[2];

		// decrypt the file
		try {
			// password variable redacted for security reasons :D
			// suffice to say the password is derived from known server variables
			$plaintext = self::aes256Decrypt($rawCipherText, $iv, $salt);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		}

		// parse the parameters and return them
		if(($parameters = parse_ini_string($plaintext)) === false) {
			throw(new InvalidArgumentException("unable to parse parameters"));
		}
		return ($parameters);
	}

	/**
	 * encrypts and writes an array of parameters to a configuration file
	 *gke
	 * @param array $parameters configuration parameters to write
	 * @throws InvalidArgumentException if the parameters are invalid or the file cannot be accessed
	 **/
	public function setSecrets(array $parameters) {

		// verify the parameters are an array
		if(is_array($parameters) === false) {
			throw(new InvalidArgumentException("parameters are not an array"));
		}

		// verify the file name is writable
		if(is_writable($this->filePath) === false) {
			throw(new InvalidArgumentException("configuration file is not writable"));
		}

		// build the plaintext to encrypt
		$plaintext = "";

		foreach($parameters as $key => $value) {

			// quote strings
			if(is_string($value) === true) {
				$value = str_replace("\"", "\\\"", $value);
				$value = "\"$value\"";
			}
			// transform booleans to "On" and "Off"
			if(is_bool($value)) {
				if($value === true) {
					$value = "On";
				} else {
					$value = "Off";
				}
			}

			$plaintext = $plaintext . "$key = $value\n";
		}

		// delete the final newline
		$plaintext = substr($plaintext, 0, -1);

		// encrypt the text using the filename
		$ciphertext = self::aes256Encrypt($plaintext);

		// open the config file and write the cipher text
		if(file_put_contents($this->filePath, $ciphertext) === false) {
			throw(new InvalidArgumentException("unable to write configuration file"));
		}
	}

	/**
	 * connects to a mySQL database using the encrypted mySQL configuration
	 *
	 * @return \PDO connection to mySQL
	 **/
	public function getPdoObject(): \PDO {

		//grab the environment variables from the host.
		$env = getenv();

		// grab the encrypted mySQL properties file and crete the DSN
		$dsn = "mysql:host=" . $env["MYSQL_HOST"] . ";dbname=" . $env["MYSQL_DATABASE"];
		$options = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
		$pdo = new PDO($dsn, $env["MYSQL_USER"], $env["MYSQL_PASSWORD"], $options);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return ($pdo);
	}

	/**
	 * Function that will return an object of protected variables.
	 *
	 * @param string $needle associative array key of the protected config object
	 * @return object $secret object containing the specified secret TLDR API keys
	 **/

	public function getSecret(string $needle): object {

		// unencrypt the secrets array
		$secrets = self::getSecrets();

		// search for the needle in the haystack.
		$secret = $secrets[$needle] ?? (bool)false;


		//json decode the secret object
		$secret = json_decode($secret);

		if(is_object($secret) === false) {
			throw new \InvalidArgumentException("needle was not found");
		}

		return (object)$secret;
	}

	/**
	 * accessor function that will return the database name from the secrets array.
	 * @return string $databaseName value of $secret["database"]
	 */

	public function getDatabase() : string {
		$databaseName = self::getSecrets();

		return $databaseName["database"];

	}

	/**
	 * decrypts text using AES 256 CBC mode using openssl_decrypt()
	 *
	 * @param string $ciphertext base 64 encoded ciphertext
	 * @param string $iv $iv used for encryption/decryption
	 * @param string $salt salt used for encryption and decryption
	 * @return string decrypted plaintext
	 * @throws InvalidArgumentException if the pla
	 * @see http://php.net/manual/en/function.openssl-decrypt.php
	 **/
	private function aes256Decrypt(string $ciphertext, string $iv, string $salt): string {

		//convert the ciphertext from hex to binary
		$ciphertext = hex2bin($ciphertext);

		//initialize the AES class
		$cipher = new AES();

		//set the password
		$cipher->setPassword($this->password, "pbkdf2", "sha3-256", $salt);

		//grab the iv off of the cipher text.
		$cipher->setIV($iv);

		//decrypt the cipher text
		$plaintext = $cipher->decrypt($ciphertext);

		if($plaintext === false) {
			throw new InvalidArgumentException("cipher text sucks!!", 18);
		}

		return ($plaintext);
	}

	/**
	 * encrypts text using AES 256 CBC mode using openssl_encrypt()
	 *
	 * @param string $plaintext plaintext to encrypt
	 * @return string hex encoded ciphertext
	 * @throws
	 * @see http://php.net/manual/en/function.openssl-encrypt.php
	 **/
	private function aes256Encrypt(string $plaintext): string {

		//initialize the AES class for php-sec-lib2
		$cipher = new AES();

		$salt = bin2hex(random_bytes(64));

		$cipher->setPassword($this->password, "pbkdf2", "sha3-256", $salt);
		$iv = bin2hex(random_bytes(64));
		$cipher->setIV($iv);
		$cipherText = $cipher->encrypt($plaintext);

		$cipherText = bin2hex($cipherText);
		$cipherText = $cipherText . "." . $iv . "." . $salt;

		if($cipherText === false) {
			throw new InvalidArgumentException("plaintext could not be encrypted");
		}

		return ($cipherText);
	}
}
