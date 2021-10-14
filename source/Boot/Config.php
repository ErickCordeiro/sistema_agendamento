<?php
/**
 * DATABASE LOCAL HOST
 */
define("CONF_DB_HOST", "localhost");
define("CONF_DB_USER", "root");
define("CONF_DB_PASS", "");
define("CONF_DB_NAME", "consultorio");

// define("CONF_DB_HOST", "localhost");
// define("CONF_DB_USER", "polime26_admin");
// define("CONF_DB_PASS", "OhaI@Vb]N(aL");
// define("CONF_DB_NAME", "polime26_agendamento");

/**
 * PROJECT URLs
 */
define("CONF_URL_BASE", "http://agendamento.polimedsaude.com.br"); // alterar depois para o original que vai ser publicado
define("CONF_URL_TEST", "https://www.localhost/poliseg");
define("CONF_URL_ADMIN", "/admin");

/**
 * SITE
 */
define("CONF_SITE_NAME", "Polimed - Sistema de Agendamento Online");
define("CONF_SITE_TITLE", "Polimed - Sistema de Agendamento Online");
define("CONF_SITE_DESC", "Polimed - Sistema de Agendamento Online");
define("CONF_SITE_LANG", "pt_BR");
define("CONF_SITE_DOMAIN", "http://agendamento.polimedsaude.com.br");
define("CONF_SITE_ADDR_STREET", "");
define("CONF_SITE_ADDR_NUMBER", "");
define("CONF_SITE_ADDR_COMPLEMENT", "");
define("CONF_SITE_ADDR_CITY", "");
define("CONF_SITE_ADDR_STATE", "");
define("CONF_SITE_ADDR_ZIPCODE", "");

/**
 * SOCIAL
 */
define("CONF_SOCIAL_TWITTER_CREATOR", "@erickcordeiroa");
define("CONF_SOCIAL_TWITTER_PUBLISHER", "@erickcordeiroa");
define("CONF_SOCIAL_FACEBOOK_APP", "1321853084649431");
define("CONF_SOCIAL_FACEBOOK_PAGE", "erickcordeiroa");
define("CONF_SOCIAL_FACEBOOK_AUTHOR", "erickcordeiroa");
define("CONF_SOCIAL_INSTAGRAM_PAGE", "erickcordeioa");
define("CONF_SOCIAL_GOOGLE_PAGE", "erickcordeiroa");
define("CONF_SOCIAL_GOOGLE_AUTHOR", "erickcordeiroa");

/**
 * DATES
 */
define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");

/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 6);
define("CONF_PASSWD_MAX_LEN", 10);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);

/**
 * MESSAGE
 */
define("CONF_MESSAGE_CLASS", "message");
define("CONF_MESSAGE_INFO", "trigger trigger-info icon-info radius");
define("CONF_MESSAGE_SUCCESS", "trigger trigger-success icon-success radius");
define("CONF_MESSAGE_WARNING", "trigger trigger-alert icon-warning radius");
define("CONF_MESSAGE_ERROR", "trigger trigger-error icon-danger radius");

/**
 * VIEW
 */
define("CONF_VIEW_PATH", __DIR__ . "/../../shared/views");
define("CONF_VIEW_EXT", "php");
define("CONF_VIEW_THEME", "consultorio");
define("CONF_VIEW_ADMIN", "poliadmin");

/**
 * UPLOAD
 */
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");

/**
 * IMAGES
 */
define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
define("CONF_IMAGE_SIZE", 2000);
define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

/**
 * MAIL
 */
define("CONF_MAIL_HOST", "agendamento.polimedsaude.com.br");
define("CONF_MAIL_PORT", "25");
define("CONF_MAIL_USER", "naoresponda@agendamento.polimedsaude.com.br");
define("CONF_MAIL_PASS", "vGCS8Hdpsrp0");
define("CONF_MAIL_SENDER", ["name" => CONF_SITE_NAME, "address" => "naoresponda@agendamento.polimedsaude.com.br"]);
define("CONF_MAIL_SUPPORT", "contato@ewdmarketingdigital.com.br");
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
define("CONF_MAIL_OPTION_SECURE", "tls");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");