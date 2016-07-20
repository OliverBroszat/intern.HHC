<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.07.16
 * Time: 23:35
 */

/**
 * Class WordpressConnectionError
 *
 * Gets thrown whenever connection issues with the wordpress database occur
 */
class WordpressConnectionError extends Exception { }

/**
 * Class WordpressExecutionError
 *
 * Gets thrown whenever an SQL query or database processing error occurs
 */
class WordpressExecutionError extends Exception { }

/**
 * Class ValueError
 *
 * Gets thrown whenever a key used to get an array's value is invalid
 */
class ValueError extends Exception { }