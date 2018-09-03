<?php
   define('DB_SERVER', 'localhost:3306');
   define('DB_USERNAME', 'higor');
   define('DB_PASSWORD', 'sp120c');
   define('DB_DATABASE', 'investdb');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   /* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>
