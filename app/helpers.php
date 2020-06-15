<?php

function route_class()
{
	return str_replace('.','-',Route::currentRouteName());
}

function make_excerpt($value,$length)
{
	$except = trim(preg_replace('/\r\n|\r|\n',' ',strip_tags($value)));
	return Str::limit($except,$length);
}