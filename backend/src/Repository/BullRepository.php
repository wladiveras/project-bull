<?php

namespace App\Repository;

use App\Entity\Bull;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Activation;

/**
 * @method Bull|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bull|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bull[]    findAll()
 * @method Bull[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BullRepository extends ServiceEntityRepository
{
    private $manager;
    public const PAGINATOR_PER_PAGE = 2;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, Bull::class);
        $this->manager = $manager;
    }

    public function saveBull($code, $weight, $birthday, $weekMilk, $weekFood)
    {
        $newBull = new Bull();

        $newBull
            ->setCode($code)
            ->setWeight($weight)
            ->setBirthday($birthday)
            ->setWeekMilk($weekMilk)
            ->setWeekFood($weekFood);

        $this->manager->persist($newBull);
        $this->manager->flush();
    }

    public function updateBull(Bull $bull, $data)
    {
        empty($data['code'])      ? true : $bull->setCode($data['code']);
        empty($data['weight'])    ? true : $bull->setWeight($data['weight']);
        empty($data['birthday'])  ? true : $bull->setBirthday($data['birthday']);
        empty($data['weekMilk'])  ? true : $bull->setWeekMilk($data['weekMilk']);
        empty($data['weekFood'])  ? true : $bull->setWeekFood($data['weekFood']);
        empty($data['status'])    ? true : $bull->setStatus($data['status']);
        $this->manager->flush();
    }

    public function removeBull(Bull $bull)
    {
        $this->manager->remove($bull);
        $this->manager->flush();
    }
}
