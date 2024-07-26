<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/picture', name: 'app_api_picture_')]
class PictureController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private PictureRepository $repository)
    {

    }
    #[Route(name:'new', methods: 'POST')]
        public function new():Response
        {
            $picture = new Picture();
            $picture->setTitle( title: 'Image teste');
            $picture->setSlug('Image de teste simulant un slug.');
            $picture->setCreatedAt(new \DateTimeImmutable());
            $picture->setUpdateAt(new \DateTimeImmutable());
    
            $this->manager->persist($picture);
            $this->manager->flush();
    
            return $this->json(
                ['message' => "Picture ressource created with {$picture->getId()} id"],
                Response::HTTP_CREATED,
            );
        }

        #[Route('/{id}', name:'show', methods: 'GET')]
        public function show(int $id):Response
        {
            $picture = $this->repository->findOneBy(['id' => $id]);
        if (!$picture) {
            throw new \Exception("No Picture found for {$id} id");
        }

        return $this->json(
            ['message' => "A Picture was found : {$picture->getTitle()} for {$picture->getId()} id"]
            );
        }

        #[Route('/{id}', name:'edit', methods: 'PUT')]
        public function edit(int $id):Response
        {
            $picture = $this->repository->findOneBy(['id' => $id]);
        if (!$picture) {
            throw new \Exception("No Picture found for {$id} id");
        }
        $picture->setTitle('Picture name updated');
        $picture->setSlug('Slug modified');
        $this->manager->flush();

        return $this->redirectToRoute('app_api_picture_show', ['id' => $picture->getId()]);
        }

        #[Route('/{id}', name:'delete', methods: 'DELETE')]
        public function delete(int $id):Response
        {
            $picture = $this->repository->findOneBy(['id' => $id]);
        if (!$picture) {
            throw new \Exception("No Picture found for {$id} id");
        }
        $this->manager->remove($picture);
        $this->manager->flush();

        return $this->json(['message' => 'Picture ressource deleted'], Response::HTTP_NO_CONTENT);
        }

}
