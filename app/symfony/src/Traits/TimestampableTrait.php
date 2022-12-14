<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

// class MUST implement @ORM\HasLifecycleCallbacks
trait TimestampableTrait {
    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    public function getCreatedAt(): \DateTimeInterface {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    #[ORM\PrePersist]
    public function onPrePersist() {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    #[ORM\PreUpdate]
    public function onPreUpdate() {
        $this->updatedAt = new \DateTime('now');
    }
}
