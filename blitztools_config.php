<?php

$ADMIN_USER = "admin";
$ADMIN_PASSWD = "drsadov2020";

///* herniateddiscneckpainrelief.com */
//$CONFIG["herniateddiscneckpainrelief.com"]["mysql_user"] = "herniate_herniat";
//$CONFIG["herniateddiscneckpainrelief.com"]["mysql_passwd"] = "D6$-*|fQtNFq";
//$CONFIG["herniateddiscneckpainrelief.com"]["mysql_db"] = "herniate_herniateddiscneckpainrelief";
//$CONFIG["herniateddiscneckpainrelief.com"]["host_string"] = "Neck and Disc Pain Relief"; 
//
///* 21stcenturychiropractor.com */
//$CONFIG["21stcenturychiropractor.com"]["mysql_user"] = "century";
//$CONFIG["21stcenturychiropractor.com"]["mysql_passwd"] = "drchiro88";
//$CONFIG["21stcenturychiropractor.com"]["mysql_db"] = "century_21stcentury";
//$CONFIG["21stcenturychiropractor.com"]["host_string"] = "21st Century Chiropractor";
//
///* backpainherniateddiscrelief.com */
//$CONFIG["backpainherniateddiscrelief.com"]["mysql_user"] = "backpain";
//$CONFIG["backpainherniateddiscrelief.com"]["mysql_passwd"] = "drchiro88";
//$CONFIG["backpainherniateddiscrelief.com"]["mysql_db"] = "backpain_backpainherniateddiscrelief";
//$CONFIG["backpainherniateddiscrelief.com"]["host_string"] = "Back and Disc Pain Relief";
//
///* blitzchiroblogs.com */
//$CONFIG["blitzchiroblogs.com"]["mysql_user"] = "sadov88_gator";
//$CONFIG["blitzchiroblogs.com"]["mysql_passwd"] = "Slr4dk9skTGQ";
//$CONFIG["blitzchiroblogs.com"]["mysql_db"] = "sadov88_blitzchiroblogs2";
//$CONFIG["blitzchiroblogs.com"]["host_string"] = "Blitz Chiropractic Blogger";
//
///* sciaticalegpainreliefcenter.com */
//$CONFIG["sciaticalegpainreliefcenter.com"]["mysql_user"] = "sciatica_user";
//$CONFIG["sciaticalegpainreliefcenter.com"]["mysql_passwd"] = "mM9OQDYXPKd7BAoj";
//$CONFIG["sciaticalegpainreliefcenter.com"]["mysql_db"] = "sciatica_sciatica";
//$CONFIG["sciaticalegpainreliefcenter.com"]["host_string"] = "Sciatica and Leg Pain Relief";
//
///* wordpress.mu for development purposes */
//$CONFIG["wordpress.mu"]["mysql_user"] = "root";
//$CONFIG["wordpress.mu"]["mysql_passwd"] = "l0l1p0p";
//$CONFIG["wordpress.mu"]["mysql_db"] = "wpmu";
//$CONFIG["wordpress.mu"]["host_string"] = "Local MU Development";

/* test.site for development purposes */
$CONFIG["test.site"]["mysql_user"] = "root";
$CONFIG["test.site"]["mysql_passwd"] = "l0l1p0p";
$CONFIG["test.site"]["mysql_db"] = "wpmu_testsite";
$CONFIG["test.site"]["host_string"] = "test.site MU Development";

/* back.site for development purposes */
$CONFIG["back.test"]["mysql_user"] = "root";
//$CONFIG["back.site"]["mysql_passwd"] = "l0l1p0p";
//$CONFIG["back.site"]["mysql_db"] = "back_test";
//$CONFIG["back.site"]["host_string"] = "back.test MU Development";

/* back.site for development purposes */
//$CONFIG["hernia.test"]["mysql_user"] = "root";
//$CONFIG["hernia.site"]["mysql_passwd"] = "l0l1p0p";
//$CONFIG["hernia.site"]["mysql_db"] = "hernia_test";
//$CONFIG["hernia.site"]["host_string"] = "hernia.test MU Development";
//
///* sciatica.site for development purposes */
//$CONFIG["sciatica.test"]["mysql_user"] = "root";
//$CONFIG["sciatica.site"]["mysql_passwd"] = "l0l1p0p";
//$CONFIG["sciatica.site"]["mysql_db"] = "sciatica_test";
//$CONFIG["sciatica.site"]["host_string"] = "sciatica.test MU Development";
//
///* century.site for development purposes */
//$CONFIG["century.test"]["mysql_user"] = "root";
//$CONFIG["century.site"]["mysql_passwd"] = "l0l1p0p";
//$CONFIG["century.site"]["mysql_db"] = "century_test";
//$CONFIG["century.site"]["host_string"] = "century.test MU Development";

$WGET = "/usr/bin/wget";
$MYSQL = "/usr/bin/mysql";
$MYSQL_DUMP = "/usr/bin/mysqldump";

if (function_exists('get_option')) {
	$LOCAL_HOST = substr(get_option("siteurl"), 7);
}

// to hold remote error messages
$ERR = "";

?>