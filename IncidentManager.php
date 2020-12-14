<?php

namespace AppBundle\Service;

use AppBundle\Entity\Category;
use AppBundle\Entity\Incident;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Exception\ResourceNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class IncidentManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PeopleManager
     */
    private $peopleManager;

    /**
     * @var LocationManager
     */
    private $locationManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * IncidentManager constructor.
     *
     * @param EntityManager $entityManager
     * @param ValidatorInterface $validator
     * @param PeopleManager $peopleManager
     * @param LocationManager $locationManager
     */
    public function __construct(
        EntityManager $entityManager,
        ValidatorInterface $validator,
        PeopleManager $peopleManager,
        LocationManager $locationManager
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->peopleManager = $peopleManager;
        $this->locationManager = $locationManager;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->entityManager->getRepository(Incident::class)->findAll();
    }

    /**
     * @param array $data
     *
     * @return Incident
     * @throws InvalidInputException
     */
    public function create(array $data): Incident
    {
        try {
            $this->entityManager->beginTransaction();

            /** @var Category $category */
            $category = $this->entityManager
                ->getRepository(Category::class)
                ->find($data['category']);

            if (!$category) {
                throw new ResourceNotFoundException('Category not found!');
            }

            if (!array_key_exists('incidentDate', $data)) {

                throw new InvalidInputException(['Incident date is required!!']);
            } else {
                $incidentDate = new \DateTime($data['incidentDate']);

                if (!$incidentDate instanceof \DateTime) {
                    throw new InvalidInputException(['Invalid Incident date!!']);
                }
            }

            $location = $this->locationManager->create($data);

            // Create the quote instance
            $incident = new Incident();

            $incident->setLocation($location)
                ->setCategory($category)
                ->setComments($data['comments'])
                ->setTitle($data['title'])
                ->setIncidentDate($incidentDate);

            $errors = $this->validator->validate($incident);

            if ($errors->count()) {
                throw new InvalidInputException($errors);
            }

            $this->entityManager->persist($incident);
            $this->entityManager->flush();

            $this->peopleManager->create($data, $incident);

            $this->entityManager->commit();

            $this->entityManager->refresh($incident);

        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return $incident;
    }
}