<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Repository\PictureRepository;
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

#[Route('api/picture', name: 'app_api_picture_')]
class PictureController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager, 
        private PictureRepository $repository, 
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator)
    {

    }
    #[Route(name:'new', methods: 'POST')]
    #[OA\Post(
        path: "/api/picture",
        summary: "Inscription de la nouvelle photo",
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de la nouvelle photo à inscrire',
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["title", "slug"],
                    properties: [
                        new OA\Property(
                            property: "title", 
                            type: "string", 
                            example: "Photo exemple"
                        ),
                        new OA\Property(
                            property: "slug", 
                            type: "string", 
                            example: "Description de la photo exemple"
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Photo inscrite avec succès',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "title", 
                                type: "string", 
                                example: "Photo exemple"
                            ),
                            new OA\Property(
                                property: "slug", 
                                type: "string", 
                                example: "Description de la photo exemple"
                            ),
                        ]
                    )
                )
            )
    ]
    )]
        public function new(Request $request):JsonResponse
        {
            $picture = $this->serializer->deserialize($request->getContent(), Picture::class, 'json');
            $picture->setCreatedAt(new \DateTimeImmutable());
    
            $this->manager->persist($picture);
            $this->manager->flush();
    
            $responseData = $this->serializer->serialize($picture, 'json');
            $location = $this->urlGenerator->generate(
                'app_api_picture_show',
                ['id' => $picture->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL,
            );

            return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
        }

        #[Route('/{id}', name:'show', methods: 'GET')]
        #[OA\Get(
            path: "/api/picture/{id}",
            summary: "Affichage de la photo",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID de la photo à afficher",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Photo affiché avec succès',
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
                                    example: "Picture exemple"
                                ),
                                new OA\Property(
                                    property: "slug", 
                                    type: "string", 
                                    example: "Description de la photo exemple"
                                ),
                            ]
                        )
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Photo non trouvée',
                )
       ]
        )]
        public function show(int $id):JsonResponse
        {
            $picture = $this->repository->findOneBy(['id' => $id]);
        if ($picture) {
            $responseData = $this->serializer->serialize($picture, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        #[Route('/{id}', name:'edit', methods: 'PUT')]
        #[OA\Put(
            path: "/api/picture/{id}",
            summary: "Modification du picture",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID de la photo à modifier",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            requestBody: new OA\RequestBody(
                required: true,
                description: 'Retourne les données de la photo à modifier',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["title", "slug"],
                        properties: [
                            new OA\Property(
                                property: "title", 
                                type: "string", 
                                example: "Photo exemple a modifiée"
                            ),
                            new OA\Property(
                                property: "slug", 
                                type: "string", 
                                example: "Description de la photo exemple"
                            ),
                        ]
                    )
                )
            ),
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Photo modifiée avec succès',
                    content: new OA\MediaType(
                        mediaType: "application/json",
                        schema: new OA\Schema(
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "title", 
                                    type: "string", 
                                    example: "Photo exemple a modifiée"
                                ),
                                new OA\Property(
                                    property: "slug", 
                                    type: "string", 
                                    example: "Description du photo exemple"
                                ),
                            ]
                        )
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Photo non trouvée',
                )
       ]
        )]
        public function edit(int $id, Request $request):JsonResponse
        {
            $picture = $this->repository->findOneBy(['id' => $id]);
        if ($picture) {
            $picture = $this->serializer->deserialize(
                $request->getContent(),
                Picture::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $picture]
            );
            $picture->setUpdateAt(new DateTimeImmutable());

            $this->manager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        #[Route('/{id}', name:'delete', methods: 'DELETE')]
        #[OA\Delete(
            path: "/api/picture/{id}",
            summary: "Efface la photo",
            parameters: [new OA\Parameter(
                name:"id",
                in:"path",
                required: true,
                description:"ID de la photo à effacer",
                schema: new OA\Schema(
                    type:"integer"
                 )
              )
            ],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Photo supprimée avec succès'
                ),
                new OA\Response(
                    response: 404,
                    description: 'Photo non trouvée',
                )
       ]
        )]
        #[Route('/{id}', name: 'delete', methods:'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $picture = $this->repository->findOneBy(['id' => $id]);
        if($picture){
            $this->manager->remove($picture);
            $this->manager->flush();
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}