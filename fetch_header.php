<?php
/**************************************************
**Author - Arun
**Date: August 2012.
**Description: Fetches the Header information for the url provided and output the parsed result.
**************************************************/

$url = $_GET['url'];

Fetch_Headers($url);

function Fetch_Headers($url)
{
    $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    $result= curl_exec($ch);

    $tags =  parse_header($result);

	foreach ($tags as $key=>$value)
	{
		echo "$key -- $value";
	}
}

function parse_header( $header )
{
    $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
	$retVal["Status-Code"] = $fields[0];
        foreach( $fields as $field ) {
            if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
                $match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
                if( isset($retVal[$match[1]]) ) {
                    $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
                } else {
                    $retVal[$match[1]] = trim($match[2]);
                }
            }
        }
    return $retVal;
}
?>
