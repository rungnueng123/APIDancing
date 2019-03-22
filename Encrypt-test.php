<?php
$string_to_encrypt="Test";
$password="42NTz5PQepR(Nd9";
$encrypted_string=openssl_encrypt($string_to_encrypt,"AES-128-ECB",$password);
$decrypted_string=openssl_decrypt($encrypted_string,"AES-128-ECB",$password);
echo $encrypted_string."<BR>";
echo $decrypted_string."<BR>";

?>