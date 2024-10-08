<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
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

#[Route('api/menu', name: 'app_api_menu_')]
class MenuController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager, 
        private MenuRepository $repository, 
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator)
    {

    }
    #[Route(name:'new', methods: 'POST')]
    #[OA\Post(
        path: "/api/menu",
        summary: "Inscription du nouveau menu",
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données du nouveau menu à inscrire',
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["title", "description", "price"],
                    properties: [
                        new OA\Property(
                            property: "title", 
                            type: "string", 
                            example: "Menu exemple"
                        ),
                        new OA\Property(
                            property: "Description", 
                            type: "text", 
                            example: "Description du menu exemple"
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
                description: 'Menu inscrit avec succès',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "title", 
                                type: "string", 
                                example: "Menu exemple"
                            ),
                            new OA\Property(
                                property: "Description", 
                                type: "text", 
                                example: "Description du menu exemple"
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
            $menu = $this->serializer->deserialize($request->getContent(), Menu::class, 'json');
            $menu->setCreatedAt(new \DateTimeImmutable());
    
            $this->manager->persist($menu);
            $this->manager->flush();
    
            $responseData = $this->serializer->serialize($menu, 'json');
            $location = $this->urlGenerator->generate(
                'app_api_menu_show',
                ['id' => $menu->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL,
            );

            return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
        }

        #[Route('/{id}', name:'show', methods: 'GET')]
        #[OA\Get(
            path: "/api/menu/{id}",
            summary: "Affichage du menu",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID du menu à afficher",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Menu affiché avec succès',
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
                                    example: "Menu exemple"
                                ),
                                new OA\Property(
                                    property: "Description", 
                                    type: "text", 
                                    example: "Description du menu exemple"
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
                    description: 'Menu non trouvé',
                )
       ]
        )]
        public function show(int $id):JsonResponse
        {
            $menu = $this->repository->findOneBy(['id' => $id]);
        if ($menu) {
            $responseData = $this->serializer->serialize($menu, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        #[Route('/{id}', name:'edit', methods: 'PUT')]
        #[OA\Put(
            path: "/api/menu/{id}",
            summary: "Modification du menu",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID du menu à modifier",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            requestBody: new OA\RequestBody(
                required: true,
                description: 'Retourne les données du menu à modifier',
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
                                example: "Menu exemple"
                            ),
                            new OA\Property(
                                property: "Description", 
                                type: "text", 
                                example: "Description du menu exemple"
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
                    description: 'Menu modifié avec succès',
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
                                    example: "Menu exemple"
                                ),
                                new OA\Property(
                                    property: "Description", 
                                    type: "text", 
                                    example: "Description du menu exemple"
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
                    description: 'Menu non trouvé',
                )
       ]
        )]
        public function edit(int $id, Request $request):JsonResponse
        {
            $menu = $this->repository->findOneBy(['id' => $id]);
        if ($menu) {
            $menu = $this->serializer->deserialize(
                $request->getContent(),
                Menu::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $menu]
            );
            $menu->setUpdatedAt(new DateTimeImmutable());

            $this->manager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        #[Route('/{id}', name:'delete', methods: 'DELETE')]
        #[OA\Delete(
            path: "/api/menu/{id}",
            summary: "Efface le menu",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID du menu à effacer",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Menu supprimé avec succès'
                ),
                new OA\Response(
                    response: 404,
                    description: 'Menu non trouvé',
                )
       ]
        )]
        #[Route('/{id}', name: 'delete', methods:'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $menu = $this->repository->findOneBy(['id' => $id]);
        if($menu){
            $this->manager->remove($menu);
            $this->manager->flush();
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}