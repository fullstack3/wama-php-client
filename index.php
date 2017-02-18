<?php
require("Wama.php");
error_reporting(-1); // reports all errors
ini_set("display_errors", "1"); // shows all errors
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");


// ************ SET YOUR CREDENTIALS ***************
define("USERNAME", "your@email.com");
define("PASSWORD", "your-super-secret-password");
// *************************************************


//DO NOT CHANGE
define("CLIENT_ID", "wamaapp");
define("CLIENT_SECRET", "my-secret-token-to-change-in-production");
define("URI_API", "https://www.wama.cloud/");
//END DO NOT CHANGE



$wama_obj = new Wama();
//echo $wama_obj->curl("https://www.wama.cloud/api/products");

$products = $wama_obj->get_products(null)['body'];

//uncomment the following code to see the returned data structure
// echo "<pre>";
// print_r($products);
// echo "</pre>";


foreach ($products as  &$product) {
    echo '<img src="https://www.wama.cloud/'.$product->photo.'">';
    echo "\t<strong>Code: </strong>".$product->code."\t <strong>Name: </strong>".$product->name."\t <strong>Quantity: </strong>".$product->quantity."<br />";
}






?>
