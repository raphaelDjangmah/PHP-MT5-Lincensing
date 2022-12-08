<?php


    $password = "dasilva";
    $password_b = 'my-password';

    echo password_verify($password,'$2y$10$QQITUK/U41x.PASzVac7GeoDhR7cZ1qYyar2SP2b6bqEI6sM9KUs2');
    echo "<br>";
    echo password_verify($password_b,'$2y$10$QQITUK/U41x.PASzVac7GeoDhR7cZ1qYyar2SP2b6bqEI6sM9KUs2');