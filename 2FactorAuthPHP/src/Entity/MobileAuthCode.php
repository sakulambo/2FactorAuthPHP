<?php
namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MobileAuthCodeRepository")
 * @ORM\Table(name="mobile_auth_code")
 * @ORM\HasLifecycleCallbacks()
 */
class MobileAuthCode
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @SWG\Property()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=4, nullable=false)
     * @SWG\Property()
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MobileAuth", mappedBy="mobileAuthCode")
     * @SWG\Property()
     */
    private $mobileAuth;

    /**
     * @var string
     *
     * @ORM\Column(name="expired", type="boolean", nullable=true)
     * @SWG\Property()
     *
     */
    private $expired;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @SWG\Property()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * @SWG\Property()
     */
    private $updatedAt;

    /**
     * MobileAuthCode constructor.
     */
    public function __construct()
    {
        $this->mobileAuth = new ArrayCollection();
    }


    /**
     * @return int
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
     * @return string
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
     * @return string
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param string $expired
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getMobileAuth()
    {
        return $this->mobileAuth;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new DateTime('now'));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new DateTime('now'));
        }
    }

}