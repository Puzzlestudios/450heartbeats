<?php
namespace HeartbeatsChild;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * HeartbeatsChild Autoloader
 *
 * Mirrors parent Heartbeats\Autoloader for the child namespace.
 */
class Autoloader {

	private static $default_path;
	private static $default_namespace;

	public static function run( $default_path = '', $default_namespace = '' ) {
		if ( '' === $default_path ) {
			$default_path = HEARTBEATS_CHILD_INC_PATH;
		}

		if ( '' === $default_namespace ) {
			$default_namespace = __NAMESPACE__;
		}

		self::$default_path      = $default_path;
		self::$default_namespace = $default_namespace;

		spl_autoload_register( [ __CLASS__, 'initialize_modules' ] );
	}

	private static function initialize_modules( $class ) {
		if ( 0 !== strpos( $class, self::$default_namespace . '\\' ) ) {
			return;
		}

		$relative_class_name = preg_replace( '/^' . self::$default_namespace . '\\\/', '', $class );
		$relative_class_path = strtolower( $relative_class_name );
		$final_class_path    = str_replace( '\\', '/', $relative_class_path );
		$file                = self::$default_path . '/' . $final_class_path . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
}
