<?php

error_reporting(E_ALL | E_STRICT);

set_include_path(__DIR__ . '/../vendor/SolarCore:' . get_include_path());

include 'Solar.php';
include 'Solar/Class.php';
include 'Solar/File.php';
include 'Solar/Base.php';
include 'Solar/Config.php';
include 'Solar/Markdown.php';
include 'Solar/Markdown/Wiki.php';
include 'Solar/Markdown/Plugin.php';
include 'Solar/Markdown/Plugin/Prefilter.php';
include 'Solar/Markdown/Plugin/EmStrong.php';

$markdown = new Solar_Markdown_Wiki;

$uri = substr($_SERVER['REQUEST_URI'], 1);

$prefix = realpath(__DIR__ . '/../src');


if(($sources = @glob( $prefix . '/**/*' ))){
	$links = array_filter(array_map(function($_source) use ($prefix) {
		# Matches all markdown files, not starting with a dot.
		if(preg_match('/^[^\.]+.*\.(md|markdown)$/', $_source, $match)){
			
			return pathinfo(str_replace($prefix . '/', '', $_source), PATHINFO_DIRNAME) . '/' . basename($_source, ".{$match[1]}");
		}
	}, $sources));
}

$output = '<h1>This is revived copy of Rails for PHP</h1>';

if(!empty($uri)){

	$matches = glob($prefix . '/' . $uri . '.*');
	if(empty($matches)){
		header('Status: 404 Not found');
		$output .= 'Page not found';
	}else{
		$output .= $markdown->transform(file_get_contents($matches[0]));
	}

}

$output .= '<h3>links</h3>';
foreach($links as $_link){
	$output .= "<a href='/$_link'>$_link</a><br>";
}

echo $output;