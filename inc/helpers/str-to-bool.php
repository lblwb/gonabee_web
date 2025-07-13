<?php
function str_to_bool($val)
{
	switch ($val) {
		case 'true':
			return true;
			break;
		case 'false':
			return false;
			break;
		default:
			return $val;
	}
}
