<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private SerializerInterface $serializer,
        private UserPasswordHasherInterface $passwordHasher,
    ) {  
    }
    #[Route('/registration', name: 'registration', methods: 'POST')]
    #[OA\Post(
        path: "/api/registration",
        summary: "Inscription d'un nouvel utilisateur",
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de l\'utilisateur à inscrire',
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["email", "password"],
                    properties: [
                        new OA\Property(
                            property: "email", 
                            type: "string", 
                            format: "email", 
                            example: "adresse@email.com"
                        ),
                        new OA\Property(
                            property: "password", 
                            type: "string", 
                            format: "password", 
                            example: "Mot de passe"
                        ),
                        new OA\Property(
                            property: "firstName", 
                            type: "string", 
                            example: "Nom d'utilisateur"
                        ),
                        new OA\Property(
                            property: "lastName", 
                            type: "string", 
                            example: "prenom d'utilisateur"
                        ),
                        new OA\Property(
                            property: "guestNumber", 
                            type: "smallint", 
                            example: "50"
                        ),
                        new OA\Property(
                            property: "allergy", 
                            type: "string", 
                            example: "Cacahuètes"
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Utilisateur inscrit avec succès',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "user", 
                                type: "string", 
                                example: "Mail de connexions"
                            ),
                            new OA\Property(
                                property: "apiToken", 
                                type: "string", 
                                example: "31a023e212f116124a36af14ea0c1c3806eb9378"
                            ),
                            new OA\Property(
                                property: "roles", 
                                type: "array", 
                                items: new OA\Items(
                                type: "string", 
                                example: "ROLE_USER"
                                )
                            ),
                            new OA\Property(
                                property: "firstName", 
                                type: "string", 
                                example: "Nom d'utilisateur"
                            ),
                            new OA\Property(
                                property: "lastName", 
                                type: "string", 
                                example: "prenom d'utilisateur"
                            ),
                            new OA\Property(
                                property: "guestNumber", 
                                type: "smallint", 
                                example: "50"
                            ),
                            new OA\Property(
                                property: "allergy", 
                                type: "string", 
                                example: "Cacahuètes"
                            )
                        ]
                    )
                )
            )
    ]
    )]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();
        return new JsonResponse(
            ['user' => $user->getUserIdentifier(), 'apiToken' => $user->getApiToken(), 'roles' => $user->getRoles()],
            Response::HTTP_CREATED);
    }
    #[Route('/login', name: 'login', methods: 'POST')]
    #[OA\Post(
        path: "/api/login",
        summary: "Connexion de l'utilisateur",
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de l\'utilisateur à la connexion',
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["username", "password"],
                    properties: [
                        new OA\Property(
                            property: "username", 
                            type: "string", 
                            format: "email", 
                            example: "adresse@email.com"
                        ),
                        new OA\Property(
                            property: "password", 
                            type: "string", 
                            format: "password", 
                            example: "Mot de passe"
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Utilisateur connecté avec succès',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "user", 
                                type: "string", 
                                example: "Mail de connexions"
                            ),
                            new OA\Property(
                                property: "apiToken", 
                                type: "string", 
                                example: "31a023e212f116124a36af14ea0c1c3806eb9378"
                            ),
                            new OA\Property(
                                property: "roles", 
                                type: "array", 
                                items: new OA\Items(
                                type: "string", 
                                example: "ROLE_USER"
                                )
                            ),
                        ]
                    )
                )
            )
    ]
    )]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['message' => 'missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles()
        ],);
    }
    #[Route('/account/me ', name: 'me', methods: 'GET')]
    #[OA\Get(
        path: "/api/account/me",
        summary: "Affiche le compte utilisateur",
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Retourne les données de l\'utilisateur',
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["username", "password"],
                    properties: [
                        new OA\Property(
                            property: "username", 
                            type: "string", 
                            format: "email", 
                            example: "adresse@email.com"
                        ),
                        new OA\Property(
                            property: "password", 
                            type: "string", 
                            format: "password", 
                            example: "Mot de passe"
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Donnée de l\'utilisateur',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "user", 
                                type: "string", 
                                example: "Mail de connexions"
                            ),
                            new OA\Property(
                                property: "apiToken", 
                                type: "string", 
                                example: "31a023e212f116124a36af14ea0c1c3806eb9378"
                            ),
                            new OA\Property(
                                property: "roles", 
                                type: "array", 
                                items: new OA\Items(
                                type: "string", 
                                example: "ROLE_USER"
                                )
                            ),
                            new OA\Property(
                                property: "firstName", 
                                type: "string", 
                                example: "Nom d'utilisateur"
                            ),
                            new OA\Property(
                                property: "lastName", 
                                type: "string", 
                                example: "prenom d'utilisateur"
                            ),
                            new OA\Property(
                                property: "guestNumber", 
                                type: "smallint", 
                                example: "50"
                            ),
                            new OA\Property(
                                property: "allergy", 
                                type: "string", 
                                example: "Cacahuètes"
                            )
                        ]
                    )
                )
            )
    ]
    )]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        $responseData = $this->serializer->serialize($user, 'json');

        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }
    #[Route('/account/edit', name: 'edit', methods: 'PUT')]
    public function edit(Request $request):JsonResponse
    {
        $user = $this->serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE=> $this->getUser()],
        );
        $user->setUpdatedAt(new DateTimeImmutable());

        if (isset($request->toArray()['password'])) {
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            ));
        }
            $this->manager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}