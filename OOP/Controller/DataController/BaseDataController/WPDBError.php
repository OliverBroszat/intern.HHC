<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.07.16
 * Time: 21:42
 */

/**
 * Class WPDBError
 *
 * Gets thrown whenever an action is performed that causes $wpdb->last_error not to be an empty string
 */
class WPDBError extends Exception { }