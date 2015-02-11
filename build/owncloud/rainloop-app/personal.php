<?php

/**
 * ownCloud - RainLoop mail plugin
 *
 * @author RainLoop Team
 * @copyright 2015 RainLoop Team
 *
 * https://github.com/RainLoop/rainloop-webmail/tree/master/build/owncloud
 */

OCP\User::checkLoggedIn();
OCP\App::checkAppEnabled('rainloop');

OCP\Util::addScript('rainloop', 'personal');

$sUrl = '';
$sPath = '';

$bAutologin = OCP\Config::getAppValue('rainloop', 'rainloop-autologin', false);

$bInstalledLocaly = file_exists(__DIR__.'/app/index.php');
if (!$bInstalledLocaly)
{
	$sUrl = trim(OCP\Config::getAppValue('rainloop', 'rainloop-url', ''));
	$sPath = trim(OCP\Config::getAppValue('rainloop', 'rainloop-path', ''));
}

if ($bAutologin || (!$bInstalledLocaly && ('' === $sUrl || '' === $sPath)))
{
	$oTemplate = new OCP\Template('rainloop', 'empty');
}
else
{
	$sUser = OCP\User::getUser();

	$oTemplate = new OCP\Template('rainloop', 'personal');

	$sEmail = OCP\Config::getUserValue($sUser, 'rainloop', 'rainloop-email', '');
	$sPass = OCP\Config::getUserValue($sUser, 'rainloop', 'rainloop-password', '');

	$oTemplate->assign('rainloop-email', $sEmail);
	$oTemplate->assign('rainloop-password', 0 === strlen($sPass) && 0 === strlen($sEmail) ? '' : '******');
}

return $oTemplate->fetchPage();
