<?php

namespace AppBundle\Service;

use AppBundle\Entity\Location;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Exception\ResourceNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class LocationManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * LocationManager constructor.
     *
     * @param EntityManager $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManager $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param array $data
     *
     * @return Location
     * @throws InvalidInputException
     */
    public function create(array $data): Location
    {
        try {
            $this->entityManager->beginTransaction();

            $location = new Location();

            $location->setLatitude($data['location']['latitude'])
                ->setLongitude($data['location']['longitude']);

            $errors = $this->validator->validate($location);

            if ($errors->count()) {
                throw new InvalidInputException($errors);
            }

            $this->entityManager->persist($location);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return $location;
    }
}