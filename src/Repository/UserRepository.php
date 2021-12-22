<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Accommodation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;



/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
    }

    public function addAccommodation($userbuscado, $newAccommodation)
    {
        $userbuscado->addAccommodation($newAccommodation);

        $this->manager->persist($userbuscado);
        $this->manager->persist($newAccommodation);
        $this->manager->flush();
    }

    public function getAccommodations($id)
    {
        $newUser = $this->manager
            ->getRepository(User::class)
            ->find($id);

        return $newUser->getAccommodations();
    }

    public function saveUser($name)
    {
        $newUser = new User();

        $newUser
            ->setName($name);

        $this->manager->persist($newUser);
        $this->manager->flush();
    }
}
