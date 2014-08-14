<?php
namespace LdcZfOAuth2Doctrine\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_public_keys")
 */
class PublicKey
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="LdcZfOAuth2Doctrine\Entity\Client")
     * @ORM\JoinColumn(name="client", referencedColumnName="id")
     */
    protected $client;

    /**
     * @ORM\Column(type="string")
     */
    protected $publicKey;

    /**
     * @ORM\Column(type="string")
     */
    protected $privateKey;

    /**
     * @ORM\Column(type="string")
     */
    protected $encryptionAlgorithm;

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
     * @return the $publicKey
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

	/**
     * @return the $privateKey
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

	/**
     * @param string $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

	/**
     * @param string $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

	/**
     * @return the $encryptionAlgorithm
     */
    public function getEncryptionAlgorithm()
    {
        return $this->encryptionAlgorithm;
    }

	/**
     * @param string $encryptionAlgorithm
     */
    public function setEncryptionAlgorithm($encryptionAlgorithm)
    {
        $this->encryptionAlgorithm = $encryptionAlgorithm;
    }

}
