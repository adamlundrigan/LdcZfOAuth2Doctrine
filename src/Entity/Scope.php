<?php
namespace LdcZfOAuth2Doctrine\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_scopes")
 */
class Scope
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $type = 'supported';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $scope;

    /**
     * @ORM\ManyToOne(targetEntity="LdcZfOAuth2Doctrine\Entity\Client")
     * @ORM\JoinColumn(name="client", referencedColumnName="id")
     */
    protected $client;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDefault;

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
     * @return the $type
     */
    public function getType()
    {
        return $this->type;
    }

	/**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

	/**
     * @return the $scope
     */
    public function getScope()
    {
        return $this->scope;
    }

	/**
     * @param field_type $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

	/**
     * @return the $isDefault
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

	/**
     * @param field_type $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }

}
