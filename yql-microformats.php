<?php

$url = 'http://dentedreality.com.au/contact/';

$url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20microformats%20where%20url%3D'" . urlencode( $url ) . "'&format=json";

$json = file_get_contents( $url );

print_r( json_decode( $json ) );