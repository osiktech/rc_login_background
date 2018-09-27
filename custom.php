<?php

/**
 * Background images for Roundcube login page, one for each month
 *
 * @author Petr Fojt <bluelama@liwe.cz>
 * @license GNU GPLv3+
 * @version 1.0.0
 */

header("Content-type: text/css; charset: UTF-8");

$img=date("n").".jpg";
echo "
body
{
    background-image: url('img/$img');
    background-size: 100%;
    background-repeat: no-repeat;

}
";
