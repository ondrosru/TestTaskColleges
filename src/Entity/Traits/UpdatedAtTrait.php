<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\HasLifecycleCallbacks
 */
trait UpdatedAtTrait
{
    /**
     * @var DateTime
     *
     * @Mapping\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected DateTime $updatedAt;

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }

    /**
     * @Mapping\PreUpdate
     * @Mapping\PrePersist
     */
    public function setUpdatedAt(): void {
        $this->updatedAt = new DateTime();
    }
}