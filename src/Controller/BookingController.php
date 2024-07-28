<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/booking', name: 'app_api_booking_')]
class BookingController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private BookingRepository $repository)
    {

    }
    #[Route(name:'new', methods: 'POST')]
        public function new():Response
        {
            $booking = new Booking();
            $booking->setGuestNumber('9');
            $booking->setOrderDate(new \DateTime());
            $booking->setOrderHour(new \DateTime());
            $booking->setAllergy('Allergie teste');
            $booking->setCreatedAt(new \DateTimeImmutable());
            $booking->setUpdatedAt(new \DateTimeImmutable());
    
            $this->manager->persist($booking);
            $this->manager->flush();
    
            return $this->json(
                ['message' => "Booking ressource created with {$booking->getId()} id"],
                Response::HTTP_CREATED,
            );
        }

        #[Route('/{id}', name:'show', methods: 'GET')]
        public function show(int $id):Response
        {
            $booking = $this->repository->findOneBy(['id' => $id]);
        if (!$booking) {
            throw new \Exception("No Booking found for {$id} id");
        }

        return $this->json(
            ['message' => "A Booking was found : Booking Number:{$booking->getGuestNumber()} for {$booking->getId()} id"]
            );
        }

        #[Route('/{id}', name:'edit', methods: 'PUT')]
        public function edit(int $id):Response
        {
            $booking = $this->repository->findOneBy(['id' => $id]);
        if (!$booking) {
            throw new \Exception("No Booking found for {$id} id");
        }
        $booking->setGuestNumber('12');
        $booking->setOrderDate(new \DateTime());
        $booking->setOrderHour(new \DateTime());
        $booking->setAllergy('Allergy updated');
        $this->manager->flush();

        return $this->redirectToRoute('app_api_booking_show', ['id' => $booking->getId()]);
        }

        #[Route('/{id}', name:'delete', methods: 'DELETE')]
        public function delete(int $id):Response
        {
            $booking = $this->repository->findOneBy(['id' => $id]);
        if (!$booking) {
            throw new \Exception("No Booking found for {$id} id");
        }
        $this->manager->remove($booking);
        $this->manager->flush();

        return $this->json(['message' => 'Booking ressource deleted'], Response::HTTP_NO_CONTENT);
        }

}