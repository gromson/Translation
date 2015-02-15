<?php

return array(
	'service_manager' => array(
		'allow_override' => true,
		'aliases' => array(
			'translator' => 'MvcTranslator',
		),
		'factories' => array(
			'mvcTranslator' => 'Translation\Model\TranslatorServiceFactory'
		),
	),
	'translator' => array(
		'locale' => 'en_US',
	),
);
