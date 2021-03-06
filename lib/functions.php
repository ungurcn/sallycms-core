<?php
/*
 * Copyright (c) 2013, webvariants GbR, http://www.webvariants.de
 *
 * This file is released under the terms of the MIT license. You can find the
 * complete text in the attached LICENSE file or online at:
 *
 * http://www.opensource.org/licenses/mit-license.php
 */

/**
 * @defgroup authorisation Authorisation
 * @defgroup cache         Caches
 * @defgroup controller    Controller
 * @defgroup core          Systemkern
 * @defgroup database      Datenbank
 * @defgroup event         Eventsystem
 * @defgroup form          Formular-Framework
 * @defgroup i18n          I18N
 * @defgroup layout        Layouts
 * @defgroup model         Models
 * @defgroup registry      Registry
 * @defgroup service       Services
 * @defgroup table         Tabellen
 * @defgroup util          Utilities
 */

/**
 * Simple wrapper for settype()
 *
 * Adds the special type 'raw' (no type changing) and automatically trims every
 * string.
 *
 * @param  mixed  $var   the variable to cast
 * @param  string $type  the new variable type or 'raw' of no casting should happen
 * @return mixed         the new variable value
 */
function sly_settype($var, $type) {
	if ($type !== '' && $type !== 'raw') {
		settype($var, $type);

		if ($type === 'string') {
			$var = trim($var);
		}
	}

	return $var;
}

/**
 * Searches for an array key and returns the casted value
 *
 * @param  mixed  $haystack  the array to search in
 * @param  mixed  $key       the key to find
 * @param  string $type      the new variable type or 'raw' of no casting should happen
 * @param  string $default   the default value if $key was not found
 * @return mixed             the new variable value
 */
function sly_setarraytype(array $haystack, $key, $type, $default = null) {
	return array_key_exists($key, $haystack) ? sly_settype($haystack[$key], $type) : $default;
}

/**
 * @deprecated  use sly_Request->get() instead
 *
 * @param  mixed  $name      the key to find
 * @param  string $type      the new variable type or 'raw' of no casting should happen
 * @param  string $default   the default value if $name was not found
 * @return mixed             the casted value or default
 */
function sly_get($name, $type, $default = null) {
	return sly_Core::getContainer()->get('sly-request')->get($name, $type, $default);
}

/**
 * @deprecated  use sly_Request->post() instead
 *
 * @param  mixed  $name      the key to find
 * @param  string $type      the new variable type or 'raw' of no casting should happen
 * @param  string $default   the default value if $name was not found
 * @return mixed             the casted value or default
 */
function sly_post($name, $type, $default = null) {
	return sly_Core::getContainer()->get('sly-request')->post($name, $type, $default);
}

/**
 * @deprecated  use sly_Request->request() instead
 *
 * @param  mixed  $name      the key to find
 * @param  string $type      the new variable type or 'raw' of no casting should happen
 * @param  string $default   the default value if $name was not found
 * @return mixed             the casted value or default
 */
function sly_request($name, $type, $default = null) {
	return sly_Core::getContainer()->get('sly-request')->request($name, $type, $default);
}

/**
 * @deprecated  use sly_Request->cookie() instead
 *
 * @param  mixed  $name      the key to find
 * @param  string $type      the new variable type or 'raw' of no casting should happen
 * @param  string $default   the default value if $name was not found
 * @return mixed             the casted value or default
 */
function sly_cookie($name, $type, $default = null) {
	return sly_Core::getContainer()->get('sly-request')->cookie($name, $type, $default);
}

/**
 * @deprecated  use sly_Request->getArray() instead
 *
 * @param  mixed  $name      the key to find
 * @param  string $types     the new variable type or 'raw' of no casting should happen
 * @param  string $default   the default value if $name was not found
 * @return mixed             the casted values or default
 */
function sly_getArray($name, $types, $default = array()) {
	return sly_Core::getContainer()->get('sly-request')->getArray($name, $types, $default);
}

/**
 * @deprecated  use sly_Request->postArray() instead
 *
 * @param  mixed  $name      the key to find
 * @param  string $types     the new variable type or 'raw' of no casting should happen
 * @param  string $default   the default value if $name was not found
 * @return mixed             the casted values or default
 */
function sly_postArray($name, $types, $default = array()) {
	return sly_Core::getContainer()->get('sly-request')->postArray($name, $types, $default);
}

/**
 * @deprecated  use sly_Request->requestArray() instead
 *
 * @param  mixed  $name      the key to find
 * @param  string $types     the new variable type or 'raw' of no casting should happen
 * @param  string $default   the default value if $name was not found
 * @return mixed             the casted values or default
 */
function sly_requestArray($name, $types, $default = array()) {
	return sly_Core::getContainer()->get('sly-request')->requestArray($name, $types, $default);
}

function sly_html($string) {
	return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Schlüsselbasiertes Mergen
 *
 * Gibt es hierfür eine PHP-interne Alternative?
 *
 * @param  array $array1  das erste Array
 * @param  array $array2  das zweite Array
 * @return array          das Array mit den Werten aus beiden Arrays
 */
function sly_merge($array1, $array2) {
	$result = $array1;
	foreach ($array2 as $key => $value) {
		if (!in_array($key, array_keys($result),true)) $result[$key] = $value;
	}
	return $result;
}

/**
 * Hilfsfunktion: Ersetzen von Werten in Array
 *
 * Sucht in einem Array nach Elementen und ersetzt jedes
 * Vorkommen durch einen neuen Wert.
 *
 * @param  array $array        das Such-Array
 * @param  mixed $needle       der zu suchende Wert
 * @param  mixed $replacement  der Ersetzungswert
 * @return array               das resultierende Array
 */
function sly_arrayReplace($array, $needle, $replacement) {
	// prevent endless loop
	if ($needle == $replacement) return $array;

	$i = array_search($needle, $array);
	if ($i === false) return $array;
	$array[$i] = $replacement;
	return sly_arrayReplace($array, $needle, $replacement);
}

/**
 * Hilfsfunktion: Löschen von Werten aus einem Array
 *
 * Sucht in einem Array nach Elementen und löscht jedes
 * Vorkommen.
 *
 * @param  array $array   das Such-Array
 * @param  mixed $needle  der zu suchende Wert
 * @return array          das resultierende Array
 */
function sly_arrayDelete($array, $needle) {
	$i = array_search($needle, $array);
	if ($i === false) return $array;
	unset($array[$i]);
	return sly_arrayDelete($array, $needle);
}

/**
 * Macht aus einem Skalar ein Array
 *
 * @param  mixed $element  das Element
 * @return array           leeres Array für $element = null, einelementiges
 *                         Array für $element = Skalar, sonst direkt $element
 */
function sly_makeArray($element) {
	if ($element === null)  return array();
	if (is_array($element)) return $element;
	return array($element);
}

/**
 * translate a key
 *
 * You can give more arguments than just the key to have them inserted at the
 * special placeholders ({0}, {1}, ...).
 *
 * @param  string $key  the key to find in the current language database
 * @return string       the found translation or a string like '[translate:X]'
 */
function t($key) {
	$args = func_get_args();
	$i18n = sly_Core::getContainer()->get('sly-i18n');

	if (!($i18n instanceof sly_I18N)) {
		throw new sly_Exception('No valid I18N object has been set in the DI container.');
	}

	$func = array($i18n, 'msg');
	return call_user_func_array($func, $args);
}

/**
 * translate a key and return result XHTML-encoded
 *
 * You can give more arguments than just the key to have them inserted at the
 * special placeholders ({0}, {1}, ...).
 *
 * @param  string $key  the key to find in the current language database
 * @return string       the found translation or a string like '[translate:X]' (always XHTML-safe)
 */
function ht($index) {
	$args = func_get_args();
	return sly_html(call_user_func_array('t', $args));
}

/**
 * Übersetzt den Text $text, falls dieser mit dem Präfix "translate:" beginnt.
 *
 * @param  string $text  der zu übersetzende Text
 * @param  bool   $html  wenn true, wird das Ergebnis durch sly_html() behandelt
 * @return string        der übersetzte Wert
 */
function sly_translate($text, $html = false) {
	$transKey = 'translate:';

	if (sly_Util_String::startsWith($text, $transKey)) {
		$text = t(mb_substr($text, 10));
	}

	return $html ? sly_html($text) : $text;
}

function sly_ini_get($key) {
	$res = ini_get($key);

	// key not found
	if ($res === false) {
		return $res;
	}

	$res = trim($res);

	// interpret numeric values
	if (preg_match('#(^[0-9]+)([ptgmk])$#i', $res, $matches)) {
		$last = strtolower($matches[2]);
		$res  = (int) $matches[1];

		switch ($last) {
			case 'p': $res *= 1024;
			case 't': $res *= 1024;
			case 'g': $res *= 1024;
			case 'm': $res *= 1024;
			case 'k': $res *= 1024;
		}
	}

	// interpret boolean values
	switch (strtolower($res)) {
		case 'on':
		case 'yes':
		case 'true':
			$res = true;
			break;

		case 'off':
		case 'no':
		case 'false':
			$res = false;
			break;
	}

	return $res;
}

function sly_dump() {
	print '<pre>';
	$args = func_get_args();
	call_user_func_array('var_dump', $args);
	print '</pre>';
}
