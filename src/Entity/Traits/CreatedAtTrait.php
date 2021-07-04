<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\HasLifecycleCallbacks
 */
trait CreatedAtTrait
{
    /**
     * @var DateTime
     *
     * @Mapping\Column(name="created_at", type="datetime", nullable=false)
     */
    protected DateTime $createdAt;

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    /**
     * @Mapping\PrePersist
     */
    public function setCreatedAt(): void {
        $this->createdAt = new DateTime();
    }
}