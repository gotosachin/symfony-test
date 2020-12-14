<?php

namespace AppBundle\Controller;

use AppBundle\Exception\ResourceNotFoundException;
use AppBundle\Service\IncidentManager;
use AppBundle\Service\InvalidInputException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IncidentController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @SWG\Get(
     *     summary="Retrieve a list of all carriers",
     *     path="/incidents",
     *     tags={"incident"},
     *     operationId="get_incident",
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Incident")
     *         )
     *     ),
     *     @SWG\Response(
     *        response=404,
     *        description="Carrier not found"
     *     ),
     *     @SWG\Response(
     *        response=500,
     *        description="Internal server error"
     *     )
     * )
     * @Rest\Get("/incidents")
     * @return View
     */
    public function cgetAction()
    {
        /** @var IncidentManager $incidentManager */
        $incidentManager = $this->container->get('incident_manager');

        try {
            /** @var array $incidents */
            $incidents = $incidentManager->getAll();
        } catch (NonUniqueResultException $e) {
            return $this->view('Incident not found. Error: ' . $e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (ORMException $e) {
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->view($incidents);
    }

    /**
     * @SWG\Post(
     *     summary="Create a new incident",
     *     path="/incidents",
     *     operationId="post_incident",
     *     tags={"incident"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Create a incident",
     *         required=true,
     *         @SWG\Schema(
     *             @SWG\Property(
     *                 property="category",
     *                 description="ID for the category",
     *                 type="integer"
     *             ),
     *             required={"category", "incidentDate"}
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Successfully created Incident",
     *         @SWG\Schema(ref="#/definitions/Incident")
     *     )
     * )
     * @Rest\Post("/incidents")
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     * @param Request $request
     *
     * @return View
     * @throws \Exception
     */
    public function postAction(Request $request): View
    {
        $data = json_decode($request->getContent(), true);

        try {
            /** @var IncidentManager $incidentManager */
            $incidentManager = $this->get('incident_manager');

            $incident = $incidentManager->create($data);
        } catch (ResourceNotFoundException $e) {
            return $this->view($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (InvalidInputException $e) {
            return $this->view($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        return $this->view($incident, Response::HTTP_CREATED);
    }
}