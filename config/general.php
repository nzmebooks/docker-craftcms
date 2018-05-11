<?php

/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here.
 * You can see a list of the default settings in craft/app/etc/config/defaults/general.php
 */

return array(

	// Base site URL
	'siteUrl' => null,

	// Environment-specific variables (see https://craftcms.com/docs/multi-environment-configs#environment-specific-variables)
	'environmentVariables' => array(),

	// Default Week Start Day (0 = Sunday, 1 = Monday...)
	'defaultWeekStartDay' => 0,

	// Enable CSRF Protection (recommended, will be enabled by default in Craft 3)
	'enableCsrfProtection' => true,

	// Whether "index.php" should be visible in URLs (true, false, "auto")
	'omitScriptNameInUrls' => true,

	// Control Panel trigger word
	'cpTrigger' => 'admin',

  'allowAutoUpdates' => false,

  'addTrailingSlashesToUrls' => true,

  // 32MB
  'maxUploadFileSize' => 33554432,

  'defaultImageQuality' => 75,

  'imageDriver' => 'imagick',

  'generateTransformsBeforePageLoad' => true,

  'sendPoweredByHeader' => false,

  'cacheMethod' => 'redis',

  'useEmailAsUsername' => true,

  'deferPublicRegistrationPassword' => true,

  'autoLoginAfterAccountActivation' => true,

  'verificationCodeDuration' => 'P7D',

  // In case Apache is not configured to properly save PHP session information.
  // This is the case when you keep getting automatically logged out from admin.
  //'overridePhpSessionLocation' => true,

  // User account related paths
  // 'loginPath'                   => 'login',
  // 'logoutPath'                  => 'logout',
  // 'setPasswordPath'             => 'members/set-password',
  // 'setPasswordSuccessPath'      => 'members',
  // 'activateAccountSuccessPath'  => 'members?activate=success',
  // 'activateAccountFailurePath'  => 'members?activate=fail',

	// Dev Mode (see https://craftcms.com/support/dev-mode)
	'devMode' => true,
);
