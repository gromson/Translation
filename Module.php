<?php
namespace Translation;

use Zend\Mvc\MvcEvent;
use Zend\Session\Container as SessionContainer;

class Module
{
	public function onBootstrap( MvcEvent $e )
	{
		$sm = $e->getApplication()->getServiceManager();
		$session = new SessionContainer( 'preferences' );

		if ( isset( $_GET['ln'] ) && $ln = $_GET['ln'] ) {
			$session->locale = $ln;
		} elseif ( !$session->locale ) {
			$session->locale = \Locale::acceptFromHttp( $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
		}

		\Locale::setDefault( $session->locale );

		$translator = $sm->get( 'translator' );
		$translator->setLocale( $session->locale )
			->setFallbackLocale( 'en_US' )
			->factory( $sm->get( 'config' )['translator'] );
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

}