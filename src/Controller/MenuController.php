<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/menu', name: 'app_api_menu_')]
class MenuController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private MenuRepository $repository)
    {

    }
    #[Route(name:'new', methods:'POST')]
    public function new(): Response
    {
        $menu = new Menu();
        $menu->setTitle( title: 'Menu Antique');
        $menu->setDescription('Spécialité concocté par le chef Arnaud MICHANT.');
        $menu->setPrice(20);
        $menu->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($menu);
        $this->manager->flush();

        return $this->json(
            ['message' => "Menu ressource created with {$menu->getId()} id"],
            Response::HTTP_CREATED,
        );
    }
    #[Route('/{id}', name: 'show', methods:'GET')]
    public function show(int $id): Response
    {
        $menu = $this->repository->findOneBy(['id' => $id]);
        if (!$menu) {
            throw new \Exception("No Menu found for {$id} id");
        }

        return $this->json(
            ['message' => "A menu was found : {$menu->getTitle()} for {$menu->getId()} id"]
        );
    }

    #[Route('/{id}', name: 'edit', methods:'PUT')]
    public function edit(int $id): Response
    {
        $menu = $this->repository->findOneBy(['id' => $id]);
        if(!$menu){
            throw new \Exception("No Menu  found for {$id} id");
        }
        $menu->setTitle('Menu name updated');
        $menu->setDescription('Description updated');
        $menu->setPrice(19);
        $menu->setUpdatedAt(new \DateTimeImmutable());
        $this->manager->flush();

        return $this->redirectToRoute('app_api_menu_show', ['id' => $menu->getId()]);
    }
    #[Route('/{id}', name: 'delete', methods:'DELETE')]
    public function delete(int $id): Response
    {
        $menu = $this->repository->findOneBy(['id' => $id]);
        if(!$menu){
            throw new \Exception("No Menu  found for {$id} id");
        }
        $this->manager->remove($menu);
        $this->manager->flush();

        return $this->json(['message' => 'Menu ressource deleted'], Response::HTTP_NO_CONTENT);
    }
}