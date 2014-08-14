<?php
namespace LdcZfOAuth2Doctrine;

use ZF\OAuth2\Adapter\BcryptTrait;
use OAuth2\Storage\AuthorizationCodeInterface;
use OAuth2\Storage\AccessTokenInterface;
use OAuth2\Storage\ClientCredentialsInterface;
use OAuth2\Storage\UserCredentialsInterface;
use OAuth2\Storage\RefreshTokenInterface;
use OAuth2\Storage\ScopeInterface;
use OAuth2\Storage\PublicKeyInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

/**
 * Doctrine ORM storage adapter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class DoctrineOrmAdapter implements AuthorizationCodeInterface,
    AccessTokenInterface,
    ClientCredentialsInterface,
    UserCredentialsInterface,
    RefreshTokenInterface,
    ScopeInterface,
    PublicKeyInterface
{
    use BcryptTrait;

    /**
     * Adapter Configuration
     * @var array
     */
    protected $config;

    /**
     * Doctrine Object Manager instance
     * @var ObjectManager
     */
    protected $objectManager;


    public function __construct(ObjectManager $om, $config = array())
    {
        $this->objectManager = $om;
        $this->config = array_merge(array(
            'client_entity' => 'LdcZfOAuth2Doctrine\Entity\Client',
            'access_token_entity' => 'LdcZfOAuth2Doctrine\Entity\AccessToken',
            'refresh_token_entity' => 'LdcZfOAuth2Doctrine\Entity\RefreshToken',
            'authorization_code_entity' => 'LdcZfOAuth2Doctrine\Entity\AuthorizationCode',
            'user_entity' => 'LdcZfOAuth2Doctrine\Entity\UserEntity',
            'scope_entity'  => 'LdcZfOAuth2Doctrine\Entity\Scope',
            'public_key_entity'  => 'LdcZfOAuth2Doctrine\Entity\PublicKey',
        ), $config);

        if (isset($config['bcrypt_cost'])) {
            $this->setBcryptCost($config['bcrypt_cost']);
        }
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\PublicKeyInterface::getEncryptionAlgorithm()
     */
    public function getEncryptionAlgorithm($client_id = null)
    {
        $repository = $this->objectManager->getRepository($this->config['public_key_entity']);
        $object = $repository->findOneBy(array('client' => $client_id));
        return $object instanceof $this->config['public_key_entity']
            ? $object->getEncryptionAlgorithm()
            : NULL;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\PublicKeyInterface::getPrivateKey()
     */
    public function getPrivateKey($client_id = null)
    {
        $repository = $this->objectManager->getRepository($this->config['public_key_entity']);
        $object = $repository->findOneBy(array('client' => $client_id));
        return $object instanceof $this->config['public_key_entity']
            ? $object->getPrivateKey()
            : NULL;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\PublicKeyInterface::getPublicKey()
     */
    public function getPublicKey($client_id = null)
    {
        $repository = $this->objectManager->getRepository($this->config['public_key_entity']);
        $object = $repository->findOneBy(array('client' => $client_id));
        return $object instanceof $this->config['public_key_entity']
            ? $object->getPublicKey()
            : NULL;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\RefreshTokenInterface::getRefreshToken()
     */
    public function getRefreshToken($refresh_token)
    {
        $repository = $this->objectManager->getRepository($this->config['refresh_token_entity']);
        $object = $repository->findOneBy(array('token' => $refresh_token));
        if ( ! $object instanceof $this->config['refresh_token_entity'] ) {
            return NULL;
        }
        $array = (new DoctrineObject($this->objectManager))->extract($object);
        $array['expires'] = $array['expires']->format('U');
        if (is_object($array['client']) && method_exists($array['client'], 'getID')) $array['client_id'] = $array['client']->getID();
        if (is_object($array['user']) && method_exists($array['user'], 'getID')) $array['user_id'] = $array['user']->getID();
        return $array;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\RefreshTokenInterface::setRefreshToken()
     */
    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
    {
        $object = new $this->config['refresh_token_entity'];
        $object->setToken($refresh_token);
        $object->setClient($this->getClient($client_id));
        $object->setUser($this->getUserById($user_id));
        $object->setExpires($expires);
        $object->setScope($scope);

        $this->objectManager->persist($object);
        $this->objectManager->flush();
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\RefreshTokenInterface::unsetRefreshToken()
     */
    public function unsetRefreshToken($refresh_token)
    {
        $repository = $this->objectManager->getRepository($this->config['refresh_token_entity']);
        $object = $repository->findOneBy(array('token' => $refresh_token));
        if ( $object instanceof $this->config['refresh_token_entity'] ) {
            $this->objectManager->remove($object);
            $this->objectManager->flush();
        }
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\ClientCredentialsInterface::checkClientCredentials()
     */
    public function checkClientCredentials($client_id, $client_secret = null)
    {
        $repository = $this->objectManager->getRepository($this->config['client_entity']);
        $object = $repository->findOneBy(array('id' => $client_id));
        if ( $object instanceof $this->config['client_entity'] ) {
            return $this->verifyHash($client_secret, $object->getSecret());
        }
        return false;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\ClientCredentialsInterface::isPublicClient()
     */
    public function isPublicClient($client_id)
    {
        $repository = $this->objectManager->getRepository($this->config['client_entity']);
        $object = $repository->findOneBy(array('id' => $client_id));
        if ( $object instanceof $this->config['client_entity'] ) {
            $secret = $object->getSecret();
            return empty($secret);
        }
        return false;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\ClientInterface::checkRestrictedGrantType()
     */
    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        $details = $this->getClientDetails($client_id);
        if (isset($details['grant_types'])) {
            $grant_types = explode(' ', $details['grant_types']);

            return in_array($grant_type, (array) $grant_types);
        }

        // if grant_types are not defined, then none are restricted
        return true;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\ClientInterface::getClientDetails()
     */
    public function getClientDetails($client_id)
    {
        $object = $this->getClient($client_id);
        if ( ! $object instanceof $this->config['client_entity'] ) {
            return NULL;
        }
        $array = (new DoctrineObject($this->objectManager))->extract($object);
        if (is_object($array['user']) && method_exists($array['user'], 'getID')) $array['user_id'] = $array['user']->getID();
        return $array;
    }

    public function setClientDetails($client_id, $client_secret = null, $redirect_uri = null, $grant_types = null, $scope_or_user_id = null)
    {
        if (func_num_args() > 5) {
            $scope = $scope_or_user_id;
        } else {
            $user_id = $scope_or_user_id;
            $scope   = null;
        }

        if (!empty($client_secret)) {
            $this->createBcryptHash($client_secret);
        }

        $object = $this->getClient($client_id);
        if ( ! $object instanceof $this->config['client_entity'] ) {
            $object = new $this->config['client_entity'];
        }
        $object->setId($client_id);
        $object->setSecret($client_secret);
        $object->setRedirectUri($redirect_uri);
        $object->setGrantTypes($grant_types);
        $object->setScope($scope);
        $object->setUser($this->getUserById($user_id));

        $this->objectManager->persist($object);
        $this->objectManager->flush();
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\ClientInterface::getClientScope()
     */
    public function getClientScope($client_id)
    {
        if (!$clientDetails = $this->getClientDetails($client_id)) {
            return false;
        }

        if (isset($clientDetails['scope'])) {
            return $clientDetails['scope'];
        }

        return null;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\ScopeInterface::getDefaultScope()
     */
    public function getDefaultScope($client_id = null)
    {
        $repository = $this->objectManager->getRepository($this->config['scope_entity']);
        $result = $repository->findBy(array('isDefault' => true));
        if ($result) {
            $defaultScope = array_map(function ($row) {
                return $row['scope'];
            }, $result);
            return implode(' ', $defaultScope);
        }
        return null;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\ScopeInterface::scopeExists()
     */
    public function scopeExists($scope)
    {
        $scope = explode(' ', $scope);
        $repository = $this->objectManager->getRepository($this->config['scope_entity']);
        $result = $repository->findBy(array('scope' => $scope));
        return count($result) == count($scope);
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\AuthorizationCodeInterface::expireAuthorizationCode()
     */
    public function expireAuthorizationCode($code)
    {
        $repository = $this->objectManager->getRepository($this->config['authorization_code_entity']);
        $object = $repository->findOneBy(array('code' => $code));
        if ($object instanceof $this->config['authorization_code_entity']) {
            $this->objectManager->remove($object);
            $this->objectManager->flush();
        }
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\AuthorizationCodeInterface::getAuthorizationCode()
     */
    public function getAuthorizationCode($code)
    {
        $repository = $this->objectManager->getRepository($this->config['authorization_code_entity']);
        $object = $repository->findOneBy(array('code' => $code));
        if ( ! $object instanceof $this->config['authorization_code_entity'] ) {
            return NULL;
        }
        $array = (new DoctrineObject($this->objectManager))->extract($object);
        $array['expires'] = $array['expires']->format('U');
        if (is_object($array['client']) && method_exists($array['client'], 'getID')) $array['client_id'] = $array['client']->getID();
        if (is_object($array['user']) && method_exists($array['user'], 'getID')) $array['user_id'] = $array['user']->getID();
        return $array;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\AuthorizationCodeInterface::setAuthorizationCode()
     */
    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
    {
        $repository = $this->objectManager->getRepository($this->config['authorization_code_entity']);
        $object = $repository->findOneBy(array('code' => $code));
        if ( ! $object instanceof $this->config['authorization_code_entity'] ) {
            $object = new $this->config['authorization_code_entity'];
        }
        $object->setCode($code);
        $object->setClient($this->getClient($client_id));
        $object->setUser($this->getUserById($user_id));
        $object->setRedirectUri($redirect_uri);
        $object->setExpires($expires);
        $object->setScope($scope);

        $this->objectManager->persist($object);
        $this->objectManager->flush();
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\UserCredentialsInterface::checkUserCredentials()
     */
    public function checkUserCredentials($username, $password)
    {
        $object = $this->getUserByUsername($username);
        if ( ! $object instanceof $this->config['user_entity'] ) {
            return NULL;
        }
        return $this->verifyHash($password, $object->getPassword());
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\UserCredentialsInterface::getUserDetails()
     */
    public function getUserDetails($username)
    {
        $object = $this->getUserByUsername($username);
        if ( ! $object instanceof $this->config['user_entity'] ) {
            return NULL;
        }
        return array(
            'user_id' => $object->getId(),
            'scope' => NULL,
        );
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\AccessTokenInterface::getAccessToken()
     */
    public function getAccessToken($oauth_token)
    {
        $repository = $this->objectManager->getRepository($this->config['access_token_entity']);
        $object = $repository->findOneBy(array('token' => $oauth_token));
        if ( ! $object instanceof $this->config['access_token_entity'] ) {
            return NULL;
        }
        $array = (new DoctrineObject($this->objectManager))->extract($object);
        $array['expires'] = $array['expires']->format('U');
        if (is_object($array['client']) && method_exists($array['client'], 'getID')) $array['client_id'] = $array['client']->getID();
        if (is_object($array['user']) && method_exists($array['user'], 'getID')) $array['user_id'] = $array['user']->getID();
        return $array;
    }

	/* (non-PHPdoc)
     * @see \OAuth2\Storage\AccessTokenInterface::setAccessToken()
     */
    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null)
    {
        $repository = $this->objectManager->getRepository($this->config['access_token_entity']);
        $object = $repository->findOneBy(array('token' => $oauth_token));
        if ( ! $object instanceof $this->config['access_token_entity'] ) {
            $object = new $this->config['access_token_entity'];
        }
        $object->setToken($oauth_token);

        $client = $this->getClient($client_id);
        $object->setClient($client);
        $object->setUser($client->getUser() ?: $this->getUserById($user_id));
        $object->setExpires($expires);
        $object->setScope($scope);

        $this->objectManager->persist($object);
        $this->objectManager->flush();
    }

    protected function getClient($id)
    {
        $repository = $this->objectManager->getRepository($this->config['client_entity']);
        $object = $repository->findOneBy(array('id' => $id));
        return $object;
    }

    protected function getUserById($id)
    {
        $repository = $this->objectManager->getRepository($this->config['user_entity']);
        $object = $repository->find($id);
        return $object;
    }

    protected function getUserByUsername($username)
    {
        $repository = $this->objectManager->getRepository($this->config['user_entity']);
        $object = $repository->findOneBy(array('username' => $username));
        return $object;
    }

}