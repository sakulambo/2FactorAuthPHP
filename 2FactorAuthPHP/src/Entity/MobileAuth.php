<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MobileAuthRepository")
 * @ORM\Table(name="mobile_auth")
 * @ORM\HasLifecycleCallbacks()
 * @SWG\Definition()
 */
class MobileAuth {

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
     * @ORM\Column(name="mobile_number", type="string", length=10, nullable=false)
     * @SWG\Property()
     *
     */
    private $mobileNumber;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="MobileAuthCode", inversedBy="mobileAuthCode")
     * @ORM\JoinColumn(name="mobile_auth_code_id", referencedColumnName="id")
     * @SWG\Property()
     */
    private $mobileAuthCode;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @SWG\Property()
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * @SWG\Property()
     */
    private $updatedAt;

    /**
     * MobileAuth constructor.
     * @param $mobileNumber
     * @param $mobileAuthCode
     * @throws \Exception
     */
    public function __construct($mobileNumber =  null,$mobileAuthCode = null)
    {
        if ($mobileNumber == null || $mobileAuthCode == null ) throw new \Exception("Invalid Arguments",403);
        $this->setMobileNumber($mobileNumber);
        $this->setMobileAuthCode($mobileAuthCode);
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
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * @param string $mobileNumber
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
    }

    /**
     * @return mixed
     */
    public function getMobileAuthCode()
    {
        return $this->mobileAuthCode;
    }

    /**
     * @param mixed $mobileAuthCode
     */
    public function setMobileAuthCode($mobileAuthCode)
    {
        $this->mobileAuthCode = $mobileAuthCode;
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

    public function addMobileAuthCode(MobileAuthCode $mobileAuthCode): self
    {
        if (!$this->mobileAuthCode->contains($mobileAuthCode)) {
            $this->mobileAuthCode[] = $mobileAuthCode;
            $mobileAuthCode->setMobileAuth($this);
        }

        return $this;
    }

    public function removeMobileAuthCode(MobileAuthCode $mobileAuthCode): self
    {
        if ($this->mobileAuthCode->contains($mobileAuthCode)) {
            $this->mobileAuthCode->removeElement($mobileAuthCode);
            // set the owning side to null (unless already changed)
            if ($mobileAuthCode->getMobileAuth() === $this) {
                $mobileAuthCode->setMobileAuth(null);
            }
        }

        return $this;
    }
}