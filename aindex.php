<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://adityads:animenagakure@opensky-network.org/api/states/own?serials=123456&serials=98765');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($ch);
if (curl_errno($ch)) {
	echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$x = json_decode($result);

echo "<pre>",var_dump($x),"</pre>";
?>