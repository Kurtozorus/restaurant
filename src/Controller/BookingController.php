<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/booking', name: 'app_api_booking_')]
class BookingController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager, 
        private BookingRepository $repository, 
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator)
    {

    }
    #[Route(name:'new', methods: 'POST')]
    #[OA\Post(
        path: "/api/booking",
        summary: "Inscription d'une nouvelle commande",
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de la nouvelle commande à inscrire',
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["guestNumber", "orderDate", "orderHour", "allergy"],
                    properties: [
                        new OA\Property(
                            property: "guestNumber", 
                            type: "smallint", 
                            example: "5"
                        ),
                        new OA\Property(
                            property: "orderDate", 
                            type: "string", 
                            format: "date-time", 
                            example: "2020-05-24"
                        ),
                        new OA\Property(
                            property: "orderHour", 
                            type: "string",
                            format: "hour-time",
                            example: "2020-05-24 20:00"
                        ),
                        new OA\Property(
                            property: "allergy", 
                            type: "string", 
                            example: "cacahuète"
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Commande inscrite avec succès',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "guestNumber", 
                                type: "smallint", 
                                example: "5"
                            ),
                            new OA\Property(
                                property: "orderDate", 
                                type: "string", 
                                format: "date-time", 
                                example: "2020-05-24"
                            ),
                            new OA\Property(
                                property: "orderHour", 
                                type: "string",
                                format: "hour-time",
                                example: "2020-05-24 20:00"
                            ),
                            new OA\Property(
                                property: "allergy", 
                                type: "string", 
                                example: "cacahuète"
                            )
                        ]
                    )
                )
            )
    ]
    )]
        public function new(Request $request):JsonResponse
        {
            $booking = $this->serializer->deserialize($request->getContent(), Booking::class, 'json');
            $booking->setCreatedAt(new \DateTimeImmutable());
    
            $this->manager->persist($booking);
            $this->manager->flush();
    
            $responseData = $this->serializer->serialize($booking, 'json');
            $location = $this->urlGenerator->generate(
                'app_api_restaurant_show',
                ['id' => $booking->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL,
            );

            return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
        }

        #[Route('/{id}', name:'show', methods: 'GET')]
        #[OA\Get(
            path: "/api/booking/{id}",
            summary: "Affichage de la commande",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID de la reservation à afficher",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Commande affichée avec succès',
                    content: new OA\MediaType(
                        mediaType: "application/json",
                        schema: new OA\Schema(
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "id", 
                                    type: "smallint", 
                                    example: "1"
                                ),
                                new OA\Property(
                                    property: "guestNumber", 
                                    type: "smallint", 
                                    example: "5"
                                ),
                                new OA\Property(
                                    property: "orderDate", 
                                    type: "string", 
                                    format: "date-time", 
                                    example: "2020-05-24"
                                ),
                                new OA\Property(
                                    property: "orderHour", 
                                    type: "string",
                                    format: "hour-time",
                                    example: "2020-05-24 20:00"
                                ),
                                new OA\Property(
                                    property: "allergy", 
                                    type: "string", 
                                    example: "cacahuète"
                                )
                            ]
                        )
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Commande non trouvée',
                )
       ]
        )]
        public function show(int $id):JsonResponse
        {
            $booking = $this->repository->findOneBy(['id' => $id]);
        if ($booking) {
            $responseData = $this->serializer->serialize($booking, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        #[Route('/{id}', name:'edit', methods: 'PUT')]
        #[OA\Put(
            path: "/api/booking/{id}",
            summary: "Modification de la commande",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID de la commande à modifier",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            requestBody: new OA\RequestBody(
                required: true,
                description: 'Retourne les données de la commande modifie',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["guestNumber", "orderDate", "orderHour", "allergy"],
                        properties: [
                            new OA\Property(
                                property: "guestNumber", 
                                type: "smallint", 
                                example: "5"
                            ),
                            new OA\Property(
                                property: "orderDate", 
                                type: "string", 
                                format: "date-time", 
                                example: "2020-05-24"
                            ),
                            new OA\Property(
                                property: "orderHour", 
                                type: "string",
                                format: "hour-time",
                                example: "2020-05-24 20:00"
                            ),
                            new OA\Property(
                                property: "allergy", 
                                type: "string", 
                                example: "cacahuète"
                            )
                        ]
                    )
                )
            ),
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Commande modifiée avec succès',
                    content: new OA\MediaType(
                        mediaType: "application/json",
                        schema: new OA\Schema(
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "id", 
                                    type: "integer", 
                                    example: "1"
                                ),
                                new OA\Property(
                                    property: "guestNumber", 
                                    type: "smallint", 
                                    example: "5"
                                ),
                                new OA\Property(
                                    property: "orderDate", 
                                    type: "string", 
                                    format: "date-time", 
                                    example: "2020-05-24"
                                ),
                                new OA\Property(
                                    property: "orderHour", 
                                    type: "string",
                                    format: "hour-time",
                                    example: "2020-05-24 20:00"
                                ),
                                new OA\Property(
                                    property: "allergy", 
                                    type: "string", 
                                    example: "cacahuète"
                                )
                            ]
                        )
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Commande non trouvée',
                )
       ]
        )]
        public function edit(int $id, Request $request):JsonResponse
        {
            $booking = $this->repository->findOneBy(['id' => $id]);
        if ($booking) {
            $booking = $this->serializer->deserialize(
                $request->getContent(),
                Booking::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $booking]
            );
            $booking->setUpdatedAt(new DateTimeImmutable());

            $this->manager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        #[Route('/{id}', name:'delete', methods: 'DELETE')]
        #[OA\Delete(
            path: "/api/booking/{id}",
            summary: "Efface la commande",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID de la reservation à effacer",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Commande supprimée avec succès'
                ),
                new OA\Response(
                    response: 404,
                    description: 'Commande non trouvée',
                )
       ]
        )]
        #[Route('/{id}', name: 'delete', methods:'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $booking = $this->repository->findOneBy(['id' => $id]);
        if($booking){
            $this->manager->remove($booking);
            $this->manager->flush();
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}