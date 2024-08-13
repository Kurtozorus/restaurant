<?php

namespace App\Controller;

use App\Entity\Food;
use App\Repository\FoodRepository;
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

#[Route('api/food', name: 'app_api_food_')]
class FoodController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager, 
        private FoodRepository $repository, 
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator)
    {

    }
    #[Route(name:'new', methods: 'POST')]
    #[OA\Post(
        path: "/api/food",
        summary: "Inscription de nouveau plat",
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de nouveau plat à inscrire',
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["title", "description", "price"],
                    properties: [
                        new OA\Property(
                            property: "title", 
                            type: "string", 
                            example: "Plat exemple"
                        ),
                        new OA\Property(
                            property: "Description", 
                            type: "text", 
                            example: "Description du plat exemple"
                        ),
                        new OA\Property(
                            property: "price", 
                            type: "smallint",
                            example: "15"
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Plat inscrit avec succès',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "title", 
                                type: "string", 
                                example: "Plat exemple"
                            ),
                            new OA\Property(
                                property: "Description", 
                                type: "text", 
                                example: "Description du food exemple"
                            ),
                            new OA\Property(
                                property: "price", 
                                type: "smallint",
                                example: "15"
                            ),
                        ]
                    )
                )
            )
    ]
    )]
        public function new(Request $request):JsonResponse
        {
            $food = $this->serializer->deserialize($request->getContent(), Food::class, 'json');
            $food->setCreatedAt(new \DateTimeImmutable());
    
            $this->manager->persist($food);
            $this->manager->flush();
    
            $responseData = $this->serializer->serialize($food, 'json');
            $location = $this->urlGenerator->generate(
                'app_api_food_show',
                ['id' => $food->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL,
            );

            return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
        }

        #[Route('/{id}', name:'show', methods: 'GET')]
        #[OA\Get(
            path: "/api/food/{id}",
            summary: "Affichage du plat",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID du food à afficher",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Plat affiché avec succès',
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
                                    property: "title", 
                                    type: "string", 
                                    example: "Plat exemple"
                                ),
                                new OA\Property(
                                    property: "Description", 
                                    type: "text", 
                                    example: "Description du food exemple"
                                ),
                                new OA\Property(
                                    property: "price", 
                                    type: "smallint",
                                    example: "15"
                                ),
                            ]
                        )
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Plat non trouvé',
                )
       ]
        )]
        public function show(int $id):JsonResponse
        {
            $food = $this->repository->findOneBy(['id' => $id]);
        if ($food) {
            $responseData = $this->serializer->serialize($food, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        #[Route('/{id}', name:'edit', methods: 'PUT')]
        #[OA\Put(
            path: "/api/food/{id}",
            summary: "Modification du plat",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID du plat à modifier",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            requestBody: new OA\RequestBody(
                required: true,
                description: 'Retourne les données du plat à modifier',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["title", "description", "price"],
                        properties: [
                            new OA\Property(
                                property: "id", 
                                type: "smallint", 
                                example: "1"
                            ),
                            new OA\Property(
                                property: "title", 
                                type: "string", 
                                example: "Plat exemple"
                            ),
                            new OA\Property(
                                property: "Description", 
                                type: "text", 
                                example: "Description du food exemple"
                            ),
                            new OA\Property(
                                property: "price", 
                                type: "smallint",
                                example: "10"
                            ),
                        ]
                    )
                )
            ),
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Plat modifié avec succès',
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
                                    property: "title", 
                                    type: "string", 
                                    example: "Plat exemple"
                                ),
                                new OA\Property(
                                    property: "Description", 
                                    type: "text", 
                                    example: "Description du food exemple"
                                ),
                                new OA\Property(
                                    property: "price", 
                                    type: "smallint",
                                    example: "10"
                                ),
                            ]
                        )
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Plat non trouvé',
                )
       ]
        )]
        public function edit(int $id, Request $request):JsonResponse
        {
            $food = $this->repository->findOneBy(['id' => $id]);
        if ($food) {
            $food = $this->serializer->deserialize(
                $request->getContent(),
                Food::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $food]
            );
            $food->setUpdatedAt(new DateTimeImmutable());

            $this->manager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        #[Route('/{id}', name:'delete', methods: 'DELETE')]
        #[OA\Delete(
            path: "/api/food/{id}",
            summary: "Efface le plat",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID du plat à effacer",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Plat supprimé avec succès'
                ),
                new OA\Response(
                    response: 404,
                    description: 'Plat non trouvé',
                )
       ]
        )]
        #[Route('/{id}', name: 'delete', methods:'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $food = $this->repository->findOneBy(['id' => $id]);
        if($food){
            $this->manager->remove($food);
            $this->manager->flush();
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}