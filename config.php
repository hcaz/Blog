<?php
    //Help with errors incase I need to see them
    if ($_REQUEST['error'] == 'true') {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    //DB Connection
    $password = file_get_contents('db.password');
    $conn = new mysqli('localhost', 'hcazblog', $password, 'hcazblog_blog');
    if ($conn->connect_error) {
        die('Connection failed: '.$conn->connect_error);
    }
    //Get global options from DB
    $result = $conn->query('SELECT `KEY`, `VALUE` FROM `GLOBAL`;');
    while ($row = $result->fetch_assoc()) {
        $GLOBAL[$row['KEY']] = $row['VALUE'];
    }
    //Dev message
    if ($GLOBAL['LIVE'] != 'true') {
        $painerror = $GLOBAL['LIVE'];
        require 'themes/default/plainerror.php';
        die;
    }
    //User
    $USER['LIN'] = true;
    $USER['ID'] = 1;
    $result = $conn->query("SELECT * FROM `USERS` INNER JOIN `USER_DETAILS` on `USERS`.`ID` = `USER_DETAILS`.`ID` INNER JOIN `USER_TYPES` on `USERS`.`TYPE` = `USER_TYPES`.`ID` WHERE `USERS`.`ID` = '".$USER['ID']."';");
    while ($row = $result->fetch_assoc()) {
        $USER['DETAILS'] = $row;
    }
    //Get file paths
    if ($_GET['sub'] != '') {
        $sub = $_GET['sub'];
    } else {
        $sub = null;
    }
    if ($_GET['slug'] != '') {
        $slug = $_GET['slug'];
    } else {
        $slug = null;
    }
