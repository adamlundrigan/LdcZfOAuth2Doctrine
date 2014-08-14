<?php
namespace LdcZfOAuth2Doctrine\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_authorization_codes")
 */
class AuthorizationCode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    protected $code;

    /**
     * @ORM\ManyToOne(targetEntity="LdcZfOAuth2Doctrine\Entity\Client")
     * @ORM\JoinColumn(name="client", referencedColumnName="id")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="LdcZfOAuth2Doctrine\Entity\UserEntity")
     * @ORM\JoinColumn(name="user", referencedColumnName="user_id")
     */
    protected $user;

    /**
     * @ORM\Column(type="string")
     */
    protected $redirectUri;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $expires;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $scope;

	/**
     * @return the $client
     */
    public function getClient()
    {
        return $this->client;
    }

	/**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

	/**
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
    }

	/**
     * @param UserEntity $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return the $expires
     */
    public function getExpires()
    {
        return $this->expires;
    }

	/**
     * @param \DateTime|int $expires
     */
    public function setExpires($expires)
    {
        $this->expires = ($expires instanceof \DateTime)
            ? $expires
            : new \DateTime('@'.$expires);
    }

	/**
     * @return the $scope
     */
    public function getScope()
    {
        return $this->scope;
    }

	/**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
	/**
     * @return the $code
     */
    public function getCode()
    {
        return $this->code;
    }

	/**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

	/**
     * @return the $redirectUri
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

	/**
     * @param string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }


}
