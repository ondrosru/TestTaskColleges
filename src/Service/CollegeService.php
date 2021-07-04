<?php

namespace App\Service;

use App\Entity\College;
use App\Repository\CollegeRepository;
use Doctrine\ORM\EntityManagerInterface;

class CollegeService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;
    private CollegeRepository $collegeRepository;

    /**
     * CollegeService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->collegeRepository = $entityManager->getRepository(College::class);
    }

    /**
     * @return College[]
     */
    public function getAll(): array
    {
        return $this->collegeRepository->findAll();
    }

    /**
     * @param string $name
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string|null $imgUrl
     * @param string|null $phone
     * @param string|null $website
     * @return int|null
     */
    public function createCollege(
        string $name,
        string $address,
        string $city,
        string $state,
        ?string $imgUrl = null,
        ?string $phone = null,
        ?string $website = null
    ): ?int {
        $college = new College();
        $college->setName($name);
        $college->setAddress($address);
        $college->setCity($city);
        $college->setState($state);
        if ($imgUrl) {
            $college->setImgUrl($imgUrl);
        }
        if ($phone) {
            $college->setPhone($phone);
        }
        if ($website) {
            $college->setWebsite($website);
        }
        $this->entityManager->persist($college);
        $this->entityManager->flush();

        return $college->getId();
    }

    /**
     * @param string $name
     * @return College|null
     */
    public function getCollegeByNameAndCity(string $name, string $city): ?College
    {
        return $this->collegeRepository->findOneBy(['name' => $name, 'city' => $city]);
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string|null $imgUrl
     * @param string|null $phone
     * @param string|null $website
     * @return int|null
     */
    public function updateCollege(
        int $id,
        string $name,
        string $address,
        string $city,
        string $state,
        ?string $imgUrl = null,
        ?string $phone = null,
        ?string $website = null
    ): ?int {
        $college = $this->collegeRepository->find($id);
        $college->setName($name);
        $college->setAddress($address);
        $college->setCity($city);
        $college->setState($state);
        if ($imgUrl) {
            $college->setImgUrl($imgUrl);
        }
        if ($phone) {
            $college->setPhone($phone);
        }
        if ($website) {
            $college->setWebsite($website);
        }
        $this->entityManager->flush();

        return $college->getId();
    }

    public function deleteCollege(int $id): bool
    {
        $college = $this->collegeRepository->find($id);
        if ($college === null) {
            return false;
        }
        $this->entityManager->remove($college);
        $this->entityManager->flush();
        return true;
    }
}
