<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
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

#[Route('api/catergory', name: 'app_api_catergory_')]
class CategoryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager, 
        private CategoryRepository $repository, 
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator)
    {

    }
    #[Route(name:'new', methods: 'POST')]
    #[OA\Post(
        path: "/api/catergory",
        summary: "Ajout d'une nouvelle catégorie",
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de la nouvelle catégorie',
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["title"],
                    properties: [
                        new OA\Property(
                            property: "title", 
                            type: "string", 
                            example: "Salade"
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Catégorie ajoutée avec succès',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "title", 
                                type: "string", 
                                example: "Salade"
                            )
                        ]
                    )
                )
            )
    ]
    )]
        public function new(Request $request):JsonResponse
        {
            $catergory = $this->serializer->deserialize($request->getContent(), Category::class, 'json');
            $catergory->setCreatedAt(new \DateTimeImmutable());
    
            $this->manager->persist($catergory);
            $this->manager->flush();
    
            $responseData = $this->serializer->serialize($catergory, 'json');
            $location = $this->urlGenerator->generate(
                'app_api_restaurant_show',
                ['id' => $catergory->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL,
            );

            return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
        }

        #[Route('/{id}', name:'show', methods: 'GET')]
        #[OA\Get(
            path: "/api/catergory/{id}",
            summary: "Affichage de la catégorie",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID de la catégorie à afficher",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Catégorie affichée avec succès',
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
                                    example: "Salade"
                                )
                            ]
                        )
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Catégorie non trouvée',
                )
       ]
        )]
        public function show(int $id):JsonResponse
        {
            $catergory = $this->repository->findOneBy(['id' => $id]);
        if ($catergory) {
            $responseData = $this->serializer->serialize($catergory, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        #[Route('/{id}', name:'edit', methods: 'PUT')]
        #[OA\Put(
            path: "/api/catergory/{id}",
            summary: "Modification de la catégorie",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID de la catégorie à modifier",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            requestBody: new OA\RequestBody(
                required: true,
                description: 'Retourne les données de la catégorie à modifie',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["title"],
                        properties: [
                            new OA\Property(
                                property: "title", 
                                type: "string", 
                                example: "Salade"
                            )
                        ]
                    )
                )
            ),
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Catégorie modifiée avec succès',
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
                                    example: "Salade"
                                )
                            ]
                        )
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Catégorie non trouvée',
                )
       ]
        )]
        public function edit(int $id, Request $request):JsonResponse
        {
            $catergory = $this->repository->findOneBy(['id' => $id]);
        if ($catergory) {
            $catergory = $this->serializer->deserialize(
                $request->getContent(),
                Category::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $catergory]
            );
            $catergory->setUpdatedAt(new DateTimeImmutable());

            $this->manager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        #[Route('/{id}', name:'delete', methods: 'DELETE')]
        #[OA\Delete(
            path: "/api/catergory/{id}",
            summary: "Efface la catégorie",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID de la catégorie à effacer",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Catégorie supprimée avec succès'
                ),
                new OA\Response(
                    response: 404,
                    description: 'Catégorie non trouvée',
                )
       ]
        )]
        #[Route('/{id}', name: 'delete', methods:'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $catergory = $this->repository->findOneBy(['id' => $id]);
        if($catergory){
            $this->manager->remove($catergory);
            $this->manager->flush();
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}