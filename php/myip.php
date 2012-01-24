<?php
/*
// My IP
// 

Show the remote IP address used to request this page, perform a reverse host
lookup on this IP, and then show the forward IPs from that hostname.

Copyright 2011 K and H Research Company (kandh@khresear.ch)
License: WTFPL, any version or GNU General Public License, version 3+

NOTICE: This file is no longer being maintained in this repo. It is retained
here for legacy access only, and is liable to be removed. Please obtain a 
current copy from (https://github.com/KHresearch/util/blob/live/myip.php)

*/

// Get the remote address
$address = $_SERVER["REMOTE_ADDR"];

// Stop here if all they want is their IP address
if ( @$_GET["o"]=="plain") {
	echo $address;
	die();
}

// Detect if we're serving up IPv4 or IPv6 
$address_type = strpos($address, ":") === false ? "IPv4" : "IPv6";

// Get the remote hostname(from rDNS)
$hostname = gethostbyaddr( $address );

// Get the forward records for their remote hostname(A/AAAA lookup)
$addresses = dns_get_record($hostname, DNS_ALL);

// Get the Domain used to request this page, with ipv4/6 prefix stripped off
$ourdomain = preg_replace("/^(ipv[46]\.)/", "", $_SERVER["HTTP_HOST"]);

// Get the path used to request this page
$ourpath = $_SERVER["REQUEST_URI"];

?>
<!-- 
//
// My IP
//

Show the remote IP address used to request this page, perform a reverse host 
lookup on this IP, and then show the forward IPs from that hostname.

Copyright 2011 K and H Research Company (kandh@khresear.ch)
License: WTFPL, any version or GNU General Public License, version 3+

-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" /> 
<title>What is my IP?</title>
<style type="text/css">
a:link,
a:visited {
	text-decoration: none;
	color: #00f;
}
a:hover,
a:active {
	text-decoration: none;
	color: #f00;
}
fieldset {
	margin: 0;
	border: 0;
	padding: 0;
}
#header {
	text-align: center;
	font-size: 4em;
}
#passform {
	text-align: center;
	margin: 2em;
}
#passhash {
	text-align: center;
	margin: 2em;
	font-size: 0.5em;
}
#footer {
	position: fixed;
	bottom: 0px;
	width: 99%;
}
#footer div {
	width: 500px;
	position: static;
	
	margin: 0 auto 0 auto;
	border-style: solid;
	border-width: 1px 1px 0 1px;
	border-color: #000;
	padding: 5px;
	text-align: center;
	font-size: 1.5em;
}
</style>			
</head>
<body>
<div id="header">What is my IP?</div>
<p>You are connecting via <?php echo $address_type; ?> and your address is <?php echo $address; ?>.<br />
This maps back to a reverse hostname of <?php echo $hostname; ?>.<br />
The IPs on file for this hostname are:<br /></p>
<ul>
<?php

foreach ($addresses as $key => $addr ) {
	if ( $addr["type"]=="A" ) {
		if ($addr["ip"]==$address) {
			echo "<li><b>".$addr["ip"]."</b></li>\n";
		}
		else {
			echo "<li>".$addr["ip"]."</li>\n";
		}
	}
	elseif ( $addr["type"]=="AAAA" ) {
		if ($addr["ipv6"]==$address) {
			echo "<li><b>".$addr["ipv6"]."</b></li>\n";
		}
		else {
			echo "<li>".$addr["ipv6"]."</li>\n";
		}
	}
}

?>
</ul>
<p>
<br />
<br />
<br />
Try again via <a href="http://ipv4.<?php echo $ourdomain.$ourpath; ?>">IPv4</a> or <a href="http://ipv6.<?php echo $ourdomain.$ourpath; ?>">IPv6</a>?<br>
Want your IP in <a href="http://<?php echo $_SERVER["HTTP_HOST"].$ourpath; ?>?o=plain">plaintext</a>?
</p>
<div id="footer">
	<div>Source code available on <a href="https://github.com/EugeneKay/scripts/blob/master/php/myip.php">GitHub</a></div>
</div>
</body>
</html>