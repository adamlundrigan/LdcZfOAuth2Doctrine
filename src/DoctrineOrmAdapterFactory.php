<?php
namespace LdcZfOAuth2Doctrine;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\OAuth2\Adapter\PdoAdapter;
use ZF\OAuth2\Controller\Exception;

class DoctrineOrmAdapterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     * @throws \ZF\OAuth2\Controller\Exception\RuntimeException
     * @return \ZF\OAuth2\Adapter\PdoAdapter
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config = $services->get('Configuration');

        return new DoctrineOrmAdapter(
	        $services->get($config['zf-oauth2']['object_manager']),
            @$config['zf-oauth2']['configuration'] ?: array()
        );
    }
}