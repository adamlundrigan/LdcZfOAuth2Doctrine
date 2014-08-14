<?php
namespace LdcZfOAuth2Doctrine\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_clients")
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $secret;

    /**
     * @ORM\Column(type="string")
     */
    protected $redirectUri;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $grantTypes;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $scope;

    /**
     * @ORM\ManyToOne(targetEntity="LdcZfOAuth2Doctrine\Entity\UserEntity")
     * @ORM\JoinColumn(name="user", referencedColumnName="user_id")
     */
    protected $user;

    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

	/**
     * @return the $secret
     */
    public function getSecret()
    {
        return $this->secret;
    }

	/**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
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

	/**
     * @return the $grantTypes
     */
    public function getGrantTypes()
    {
        return $this->grantTypes;
    }

	/**
     * @param string $grantTypes
     */
    public function setGrantTypes($grantTypes)
    {
        $this->grantTypes = $grantTypes;
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


}
