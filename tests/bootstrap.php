<?php
/**
 * User: Rodion Abdurakhimov
 * Mail: rodion@epages.in.ua
 * Date: 11/30/14
 * Time: 23:52
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

$_SERVER["DOCUMENT_ROOT"] = __DIR__ . '/../..';
include $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php';

function initBitrixCore()
{
    global $DB;
    $app = \Bitrix\Main\Application::getInstance();
    $con = $app->getConnection();
    $DB->db_Conn = $con->getResource();

    $_SESSION["SESS_AUTH"]["USER_ID"] = 1;
}