<?php
/**
 * User: Rodion Abdurakhimov
 * Mail: rodion.arr.web@gmail.com
 * Date: 14/3/15
 * Time: 13:25
 */

$_SERVER["DOCUMENT_ROOT"] = __DIR__ . '/../..';


require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/bx_root.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/start.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/virtual_io.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/virtual_file.php");

$application = \Bitrix\Main\Application::getInstance();
$application->initializeExtendedKernel(array(
    "get" => $_GET,
    "post" => $_POST,
    "files" => $_FILES,
    "cookie" => $_COOKIE,
    "server" => $_SERVER,
    "env" => $_ENV
));

//define global application object
$GLOBALS["APPLICATION"] = new CMain;

if(defined("SITE_ID"))
    define("LANG", SITE_ID);

if(defined("LANG"))
{
    if(defined("ADMIN_SECTION") && ADMIN_SECTION===true)
        $db_lang = CLangAdmin::GetByID(LANG);
    else
        $db_lang = CLang::GetByID(LANG);

    $arLang = $db_lang->Fetch();
}
else
{
    $arLang = $GLOBALS["APPLICATION"]->GetLang();
    define("LANG", $arLang["LID"]);
}

$lang = $arLang["LID"];
define("SITE_ID", $arLang["LID"]);
define("SITE_DIR", $arLang["DIR"]);
define("SITE_SERVER_NAME", $arLang["SERVER_NAME"]);
define("SITE_CHARSET", $arLang["CHARSET"]);
define("FORMAT_DATE", $arLang["FORMAT_DATE"]);
define("FORMAT_DATETIME", $arLang["FORMAT_DATETIME"]);
define("LANG_DIR", $arLang["DIR"]);
define("LANG_CHARSET", $arLang["CHARSET"]);
define("LANG_ADMIN_LID", $arLang["LANGUAGE_ID"]);
define("LANGUAGE_ID", $arLang["LANGUAGE_ID"]);

$context = $application->getContext();
/** @var \Bitrix\Main\HttpRequest $request */
$request = $context->getRequest();
if (!$request->isAdminSection())
{
    $context->setSite(SITE_ID);
}
$context->setLanguage(LANGUAGE_ID);
$context->setCulture(new \Bitrix\Main\Context\Culture($arLang));

$application->start();

$GLOBALS["APPLICATION"]->reinitPath();

if (!defined("POST_FORM_ACTION_URI"))
{
    define("POST_FORM_ACTION_URI", htmlspecialcharsbx(GetRequestUri()));
}

$GLOBALS["MESS"] = array();
$GLOBALS["ALL_LANG_FILES"] = array();
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/tools.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/database.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/main.php");
IncludeModuleLangFile(__FILE__);

error_reporting(COption::GetOptionInt("main", "error_reporting", E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR|E_PARSE) & ~E_STRICT);

if(!defined("BX_COMP_MANAGED_CACHE") && COption::GetOptionString("main", "component_managed_cache_on", "Y") <> "N")
{
    define("BX_COMP_MANAGED_CACHE", true);
}

require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/filter_tools.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/ajax_tools.php");


class CBXFeatures
{
    public static function IsFeatureEnabled($featureId)
    {
        return true;
    }

    public static function IsFeatureEditable($featureId)
    {
        return true;
    }

    public static function SetFeatureEnabled($featureId, $bEnabled = true)
    {
    }

    public static function SaveFeaturesSettings($arEnabledEditions, $arEnabledFeatures)
    {
    }

    public static function GetFeaturesList()
    {
        return array();
    }

    public static function InitiateEditionsSettings($arEditions)
    {
    }

    public static function ModifyFeaturesSettings($arEditions, $arFeatures)
    {
    }

    public static function IsFeatureInstalled($featureId)
    {
        return true;
    }
}
//Do not remove this

//component 2.0 template engines
$GLOBALS["arCustomTemplateEngines"] = array();

require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/urlrewriter.php");

/**
 * Defined in dbconn.php
 * @param string $DBType
 */

\Bitrix\Main\Loader::registerAutoLoadClasses(
    "main",
    array(
        "CSiteTemplate" => "classes/general/site_template.php",
        "CBitrixComponent" => "classes/general/component.php",
        "CComponentEngine" => "classes/general/component_engine.php",
        "CComponentAjax" => "classes/general/component_ajax.php",
        "CBitrixComponentTemplate" => "classes/general/component_template.php",
        "CComponentUtil" => "classes/general/component_util.php",
        "CControllerClient" => "classes/general/controller_member.php",
        "PHPParser" => "classes/general/php_parser.php",
        "CDiskQuota" => "classes/".$DBType."/quota.php",
        "CEventLog" => "classes/general/event_log.php",
        "CEventMain" => "classes/general/event_log.php",
        "CAdminFileDialog" => "classes/general/file_dialog.php",
        "WLL_User" => "classes/general/liveid.php",
        "WLL_ConsentToken" => "classes/general/liveid.php",
        "WindowsLiveLogin" => "classes/general/liveid.php",
        "CAllFile" => "classes/general/file.php",
        "CFile" => "classes/".$DBType."/file.php",
        "CTempFile" => "classes/general/file_temp.php",
        "CFavorites" => "classes/".$DBType."/favorites.php",
        "CUserOptions" => "classes/general/user_options.php",
        "CGridOptions" => "classes/general/grids.php",
        "CUndo" => "/classes/general/undo.php",
        "CAutoSave" => "/classes/general/undo.php",
        "CRatings" => "classes/".$DBType."/ratings.php",
        "CRatingsComponentsMain" => "classes/".$DBType."/ratings_components.php",
        "CRatingRule" => "classes/general/rating_rule.php",
        "CRatingRulesMain" => "classes/".$DBType."/rating_rules.php",
        "CTopPanel" => "public/top_panel.php",
        "CEditArea" => "public/edit_area.php",
        "CComponentPanel" => "public/edit_area.php",
        "CTextParser" => "classes/general/textparser.php",
        "CPHPCacheFiles" => "classes/general/cache_files.php",
        "CDataXML" => "classes/general/xml.php",
        "CXMLFileStream" => "classes/general/xml.php",
        "CRsaProvider" => "classes/general/rsasecurity.php",
        "CRsaSecurity" => "classes/general/rsasecurity.php",
        "CRsaBcmathProvider" => "classes/general/rsabcmath.php",
        "CRsaOpensslProvider" => "classes/general/rsaopenssl.php",
        "CASNReader" => "classes/general/asn.php",
        "CBXShortUri" => "classes/".$DBType."/short_uri.php",
        "CFinder" => "classes/general/finder.php",
        "CAccess" => "classes/general/access.php",
        "CAuthProvider" => "classes/general/authproviders.php",
        "IProviderInterface" => "classes/general/authproviders.php",
        "CGroupAuthProvider" => "classes/general/authproviders.php",
        "CUserAuthProvider" => "classes/general/authproviders.php",
        "CTableSchema" => "classes/general/table_schema.php",
        "CCSVData" => "classes/general/csv_data.php",
        "CSmile" => "classes/general/smile.php",
        "CSmileSet" => "classes/general/smile.php",
        "CUserCounter" => "classes/".$DBType."/user_counter.php",
        "CHotKeys" => "classes/general/hot_keys.php",
        "CHotKeysCode" => "classes/general/hot_keys.php",
        "CBXSanitizer" => "classes/general/sanitizer.php",
        "CBXArchive" => "classes/general/archive.php",
        "CAdminNotify" => "classes/general/admin_notify.php",
        "CBXFavAdmMenu" => "classes/general/favorites.php",
        "CAdminInformer" => "classes/general/admin_informer.php",
        "CSiteCheckerTest" => "classes/general/site_checker.php",
        "CSqlUtil" => "classes/general/sql_util.php",
        "CHTMLPagesCache" => "classes/general/cache_html.php",
        "CFileUploader" => "classes/general/uploader.php",
    )
);

require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/".$DBType."/agent.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/".$DBType."/user.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/".$DBType."/event.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/menu.php");
AddEventHandler("main", "OnAfterEpilog", array("\\Bitrix\\Main\\Data\\ManagedCache", "finalize"));
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/".$DBType."/usertype.php");

if(file_exists(($_fname = $_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/update_db_updater.php")))
{
    $US_HOST_PROCESS_MAIN = False;
    include($_fname);
}

$GLOBALS["APPLICATION"]->AddJSKernelInfo(
    'main',
    array(
        '/bitrix/js/main/core/core.js', '/bitrix/js/main/core/core_ajax.js', '/bitrix/js/main/json/json2.min.js',
        '/bitrix/js/main/core/core_ls.js', '/bitrix/js/main/core/core_popup.js', '/bitrix/js/main/core/core_tooltip.js',
        '/bitrix/js/main/core/core_date.js','/bitrix/js/main/core/core_timer.js', '/bitrix/js/main/core/core_fx.js',
        '/bitrix/js/main/core/core_window.js', '/bitrix/js/main/core/core_autosave.js', '/bitrix/js/main/rating_like.js',
        '/bitrix/js/main/session.js', '/bitrix/js/main/dd.js', '/bitrix/js/main/utils.js',
        '/bitrix/js/main/core/core_dd.js', '/bitrix/js/main/core/core_webrtc.js'
    )
);


$GLOBALS["APPLICATION"]->AddCSSKernelInfo(
    'main',
    array(
        '/bitrix/js/main/core/css/core.css', '/bitrix/js/main/core/css/core_popup.css',
        '/bitrix/js/main/core/css/core_tooltip.css', '/bitrix/js/main/core/css/core_date.css'
    )
);

//Park core uploader
$GLOBALS["APPLICATION"]->AddJSKernelInfo(
    'coreuploader',
    array(
        '/bitrix/js/main/core/core_uploader/common.js',
        '/bitrix/js/main/core/core_uploader/uploader.js',
        '/bitrix/js/main/core/core_uploader/file.js',
        '/bitrix/js/main/core/core_uploader/queue.js',
    )
);

if(file_exists(($_fname = $_SERVER["DOCUMENT_ROOT"]."/bitrix/init.php")))
    include_once($_fname);

if(($_fname = getLocalPath("php_interface/init.php", BX_PERSONAL_ROOT)) !== false)
    include_once($_SERVER["DOCUMENT_ROOT"].$_fname);

if(($_fname = getLocalPath("php_interface/".SITE_ID."/init.php", BX_PERSONAL_ROOT)) !== false)
    include_once($_SERVER["DOCUMENT_ROOT"].$_fname);

if(!defined("BX_FILE_PERMISSIONS"))
    define("BX_FILE_PERMISSIONS", 0644);
if(!defined("BX_DIR_PERMISSIONS"))
    define("BX_DIR_PERMISSIONS", 0755);

//global var, is used somewhere
$GLOBALS["sDocPath"] = $GLOBALS["APPLICATION"]->GetCurPage();

if((!(defined("STATISTIC_ONLY") && STATISTIC_ONLY && substr($GLOBALS["APPLICATION"]->GetCurPage(), 0, strlen(BX_ROOT."/admin/"))!=BX_ROOT."/admin/")) && COption::GetOptionString("main", "include_charset", "Y")=="Y" && strlen(LANG_CHARSET)>0)
    header("Content-Type: text/html; charset=".LANG_CHARSET);

if(COption::GetOptionString("main", "set_p3p_header", "Y")=="Y")
    header("P3P: policyref=\"/bitrix/p3p.xml\", CP=\"NON DSP COR CUR ADM DEV PSA PSD OUR UNR BUS UNI COM NAV INT DEM STA\"");

//licence key
$LICENSE_KEY = "";
if(file_exists(($_fname = $_SERVER["DOCUMENT_ROOT"].BX_ROOT."/license_key.php")))
    include($_fname);
if($LICENSE_KEY == "" || strtoupper($LICENSE_KEY) == "DEMO")
    define("LICENSE_KEY", "DEMO");
else
    define("LICENSE_KEY", $LICENSE_KEY);

header("X-Powered-CMS: Bitrix Site Manager (".(LICENSE_KEY == "DEMO"? "DEMO" : md5("BITRIX".LICENSE_KEY."LICENCE")).")");

define("BX_CRONTAB_SUPPORT", defined("BX_CRONTAB"));

//session initialization
ini_set("session.cookie_httponly", "1");

if($domain = $GLOBALS["APPLICATION"]->GetCookieDomain())
    ini_set("session.cookie_domain", $domain);

if(COption::GetOptionString("security", "session", "N") === "Y"	&& CModule::IncludeModule("security"))
    CSecuritySession::Init();

session_start();

foreach (GetModuleEvents("main", "OnPageStart", true) as $arEvent)
    ExecuteModuleEventEx($arEvent);

//define global user object
$GLOBALS["USER"] = new CUser;

//session control from group policy
$arPolicy = $GLOBALS["USER"]->GetSecurityPolicy();
$currTime = time();
if(
    (
        //IP address changed
        $_SESSION['SESS_IP']
        && strlen($arPolicy["SESSION_IP_MASK"])>0
        && (
            (ip2long($arPolicy["SESSION_IP_MASK"]) & ip2long($_SESSION['SESS_IP']))
            !=
            (ip2long($arPolicy["SESSION_IP_MASK"]) & ip2long($_SERVER['REMOTE_ADDR']))
        )
    )
    ||
    (
        //session timeout
        $arPolicy["SESSION_TIMEOUT"]>0
        && $_SESSION['SESS_TIME']>0
        && $currTime-$arPolicy["SESSION_TIMEOUT"]*60 > $_SESSION['SESS_TIME']
    )
    ||
    (
        //session expander control
        isset($_SESSION["BX_SESSION_TERMINATE_TIME"])
        && $_SESSION["BX_SESSION_TERMINATE_TIME"] > 0
        && $currTime > $_SESSION["BX_SESSION_TERMINATE_TIME"]
    )
    ||
    (
        //signed session
        isset($_SESSION["BX_SESSION_SIGN"])
        && $_SESSION["BX_SESSION_SIGN"] <> bitrix_sess_sign()
    )
    ||
    (
        //session manually expired, e.g. in $User->LoginHitByHash
    isSessionExpired()
    )
)
{
    $_SESSION = array();
    @session_destroy();

    //session_destroy cleans user sesssion handles in some PHP versions
    //see http://bugs.php.net/bug.php?id=32330 discussion
    if(COption::GetOptionString("security", "session", "N") === "Y"	&& CModule::IncludeModule("security"))
        CSecuritySession::Init();

    session_id(md5(uniqid(rand(), true)));
    session_start();
    $GLOBALS["USER"] = new CUser;
}
$_SESSION['SESS_IP'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['SESS_TIME'] = time();
if(!isset($_SESSION["BX_SESSION_SIGN"]))
    $_SESSION["BX_SESSION_SIGN"] = bitrix_sess_sign();

//session control from security module
if(
    (COption::GetOptionString("main", "use_session_id_ttl", "N") == "Y")
    && (COption::GetOptionInt("main", "session_id_ttl", 0) > 0)
    && !defined("BX_SESSION_ID_CHANGE")
)
{
    if(!array_key_exists('SESS_ID_TIME', $_SESSION))
    {
        $_SESSION['SESS_ID_TIME'] = $_SESSION['SESS_TIME'];
    }
    elseif(($_SESSION['SESS_ID_TIME'] + COption::GetOptionInt("main", "session_id_ttl")) < $_SESSION['SESS_TIME'])
    {
        if(COption::GetOptionString("security", "session", "N") === "Y" && CModule::IncludeModule("security"))
        {
            CSecuritySession::UpdateSessID();
        }
        else
        {
            session_regenerate_id();
        }
        $_SESSION['SESS_ID_TIME'] = $_SESSION['SESS_TIME'];
    }
}

define("BX_STARTED", true);

if (isset($_SESSION['BX_ADMIN_LOAD_AUTH']))
{
    define('ADMIN_SECTION_LOAD_AUTH', 1);
    unset($_SESSION['BX_ADMIN_LOAD_AUTH']);
}

if(!defined("NOT_CHECK_PERMISSIONS") || NOT_CHECK_PERMISSIONS!==true)
{
    $bLogout = isset($_REQUEST["logout"]) && (strtolower($_REQUEST["logout"]) == "yes");

    if($bLogout && $GLOBALS["USER"]->IsAuthorized())
    {
        $GLOBALS["USER"]->Logout();
        LocalRedirect($GLOBALS["APPLICATION"]->GetCurPageParam('', array('logout')));
    }

    // authorize by cookies
    if(!$GLOBALS["USER"]->IsAuthorized())
    {
        $GLOBALS["USER"]->LoginByCookies();
    }

    $arAuthResult = false;

    //http basic and digest authorization
    if(($httpAuth = $GLOBALS["USER"]->LoginByHttpAuth()) !== null)
    {
        $arAuthResult = $httpAuth;
        $GLOBALS["APPLICATION"]->SetAuthResult($arAuthResult);
    }

    //Authorize user from authorization html form
    if(isset($_REQUEST["AUTH_FORM"]) && $_REQUEST["AUTH_FORM"] <> '')
    {
        $bRsaError = false;
        if(COption::GetOptionString('main', 'use_encrypted_auth', 'N') == 'Y')
        {
            //possible encrypted user password
            $sec = new CRsaSecurity();
            if(($arKeys = $sec->LoadKeys()))
            {
                $sec->SetKeys($arKeys);
                $errno = $sec->AcceptFromForm(array('USER_PASSWORD', 'USER_CONFIRM_PASSWORD'));
                if($errno == CRsaSecurity::ERROR_SESS_CHECK)
                    $arAuthResult = array("MESSAGE"=>GetMessage("main_include_decode_pass_sess"), "TYPE"=>"ERROR");
                elseif($errno < 0)
                    $arAuthResult = array("MESSAGE"=>GetMessage("main_include_decode_pass_err", array("#ERRCODE#"=>$errno)), "TYPE"=>"ERROR");

                if($errno < 0)
                    $bRsaError = true;
            }
        }

        if($bRsaError == false)
        {
            if(!defined("ADMIN_SECTION") || ADMIN_SECTION !== true)
                $USER_LID = LANG;
            else
                $USER_LID = false;

            if($_REQUEST["TYPE"] == "AUTH")
            {
                $arAuthResult = $GLOBALS["USER"]->Login($_REQUEST["USER_LOGIN"], $_REQUEST["USER_PASSWORD"], $_REQUEST["USER_REMEMBER"]);
            }
            elseif($_REQUEST["TYPE"] == "OTP")
            {
                $arAuthResult = $GLOBALS["USER"]->LoginByOtp($_REQUEST["USER_OTP"], $_REQUEST["OTP_REMEMBER"], $_REQUEST["captcha_word"], $_REQUEST["captcha_sid"]);
            }
            elseif($_REQUEST["TYPE"] == "SEND_PWD")
            {
                $arAuthResult = $GLOBALS["USER"]->SendPassword($_REQUEST["USER_LOGIN"], $_REQUEST["USER_EMAIL"], $USER_LID);
            }
            elseif($_SERVER['REQUEST_METHOD'] == 'POST' && $_REQUEST["TYPE"] == "CHANGE_PWD")
            {
                $arAuthResult = $GLOBALS["USER"]->ChangePassword($_REQUEST["USER_LOGIN"], $_REQUEST["USER_CHECKWORD"], $_REQUEST["USER_PASSWORD"], $_REQUEST["USER_CONFIRM_PASSWORD"], $USER_LID);
            }
            elseif(COption::GetOptionString("main", "new_user_registration", "N") == "Y" && $_SERVER['REQUEST_METHOD'] == 'POST' && $_REQUEST["TYPE"] == "REGISTRATION" && (!defined("ADMIN_SECTION") || ADMIN_SECTION!==true))
            {
                $arAuthResult = $GLOBALS["USER"]->Register($_REQUEST["USER_LOGIN"], $_REQUEST["USER_NAME"], $_REQUEST["USER_LAST_NAME"], $_REQUEST["USER_PASSWORD"], $_REQUEST["USER_CONFIRM_PASSWORD"], $_REQUEST["USER_EMAIL"], $USER_LID, $_REQUEST["captcha_word"], $_REQUEST["captcha_sid"]);
            }

            if($_REQUEST["TYPE"] == "AUTH" || $_REQUEST["TYPE"] == "OTP")
            {
                //special login form in the control panel
                if($arAuthResult === true && defined('ADMIN_SECTION') && ADMIN_SECTION === true)
                {
                    //store cookies for next hit (see CMain::GetSpreadCookieHTML())
                    $GLOBALS["APPLICATION"]->StoreCookies();
                    $_SESSION['BX_ADMIN_LOAD_AUTH'] = true;
                    echo '<script type="text/javascript">window.onload=function(){top.BX.AUTHAGENT.setAuthResult(false);};</script>';
                    die();
                }
            }
        }
        $GLOBALS["APPLICATION"]->SetAuthResult($arAuthResult);
    }
    elseif(!$GLOBALS["USER"]->IsAuthorized())
    {
        //Authorize by unique URL
        $GLOBALS["USER"]->LoginHitByHash();
    }
}

//application password scope control
if(($applicationID = $GLOBALS["USER"]->GetParam("APPLICATION_ID")) !== null)
{
    $appManager = \Bitrix\Main\Authentication\ApplicationManager::getInstance();
    if($appManager->checkScope($applicationID) !== true)
    {
        CHTTP::SetStatus("403 Forbidden");
        die();
    }
}

//define the site template
if(!defined("ADMIN_SECTION") || ADMIN_SECTION !== true)
{
    if(isset($_REQUEST["bitrix_preview_site_template"]) && $_REQUEST["bitrix_preview_site_template"] <> "" && $GLOBALS["USER"]->CanDoOperation('view_other_settings'))
    {
        //preview of site template
        $aTemplates = CSiteTemplate::GetByID($_REQUEST["bitrix_preview_site_template"]);
        if($template = $aTemplates->Fetch())
            define("SITE_TEMPLATE_ID", $template["ID"]);
        else
            define("SITE_TEMPLATE_ID", CSite::GetCurTemplate());
    }
    else
    {
        define("SITE_TEMPLATE_ID", CSite::GetCurTemplate());
    }

    define("SITE_TEMPLATE_PATH", getLocalPath('templates/'.SITE_TEMPLATE_ID, BX_PERSONAL_ROOT));
}

//magic parameters: show page creation time
if(isset($_GET["show_page_exec_time"]))
{
    if($_GET["show_page_exec_time"]=="Y" || $_GET["show_page_exec_time"]=="N")
        $_SESSION["SESS_SHOW_TIME_EXEC"] = $_GET["show_page_exec_time"];
}

//magic parameters: show included file processing time
if(isset($_GET["show_include_exec_time"]))
{
    if($_GET["show_include_exec_time"]=="Y" || $_GET["show_include_exec_time"]=="N")
        $_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"] = $_GET["show_include_exec_time"];
}

//magic parameters: show include areas
if(isset($_GET["bitrix_include_areas"]) && $_GET["bitrix_include_areas"] <> "")
    $GLOBALS["APPLICATION"]->SetShowIncludeAreas($_GET["bitrix_include_areas"]=="Y");

//magic sound
if($GLOBALS["USER"]->IsAuthorized())
{
    $cookie_prefix = COption::GetOptionString('main', 'cookie_name', 'BITRIX_SM');
    if(!isset($_COOKIE[$cookie_prefix.'_SOUND_LOGIN_PLAYED']))
        $GLOBALS["APPLICATION"]->set_cookie('SOUND_LOGIN_PLAYED', 'Y', 0);
}

//magic cache
\Bitrix\Main\Page\Frame::shouldBeEnabled();

foreach(GetModuleEvents("main", "OnBeforeProlog", true) as $arEvent)
    ExecuteModuleEventEx($arEvent);

//Do not remove this

if(isset($REDIRECT_STATUS) && $REDIRECT_STATUS==404)
{
    if(COption::GetOptionString("main", "header_200", "N")=="Y")
        CHTTP::SetStatus("200 OK");
}
