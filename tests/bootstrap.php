<?php
/**
 * User: Rodion Abdurakhimov
 * Mail: rodion@epages.in.ua
 * Date: 11/30/14
 * Time: 23:52
 */

require_once "main_include_no_permission.php";

function initBitrixCore()
{
    global $DB;
    $app = \Bitrix\Main\Application::getInstance();
    $con = $app->getConnection();
    $DB->db_Conn = $con->getResource();

    $_SESSION["SESS_AUTH"]["USER_ID"] = 1;
}
