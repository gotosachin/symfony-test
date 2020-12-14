<?php

namespace AppBundle\Service;

use AppBundle\Entity\Incident;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\People;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class PeopleManager
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
     * PeopleManager constructor.
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
     * @param Incident $incident
     *
     * @return People
     * @throws InvalidInputException
     */
    public function create(array $data, Incident $incident): People
    {
        try {
            $this->entityManager->beginTransaction();

            if (!array_key_exists('people', $data) || empty($data['people'])) {
                throw new InvalidInputException(['Incident people required!!']);
            }

            foreach ($data['people'] as $people) {
                $peopleObj = new People();
                $peopleObj->setName($people['name'])
                    ->setType($people['type'])
                    ->setIncident($incident);

                $errors = $this->validator->validate($peopleObj);

                if ($errors->count()) {
                    throw new InvalidInputException($errors);
                }

                $this->entityManager->persist($peopleObj);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return $peopleObj;
    }
}