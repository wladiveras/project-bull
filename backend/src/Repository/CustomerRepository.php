<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, Customer::class);
        $this->manager = $manager;
    }

    public function saveCustomer($name, $email, $age, $role)
    {
        $newCustomer = new Customer();

        $newCustomer
            ->setName($name)
            ->setAge($age)
            ->setEmail($email)
            ->setRole($role);

        $this->manager->persist($newCustomer);
        $this->manager->flush();
    }

    public function updateCustomer(Customer $customer, $data)
    {
        empty($data['name'])  ? true : $customer->setName($data['name']);
        empty($data['age'])   ? true : $customer->setAge($data['age']);
        empty($data['email']) ? true : $customer->setEmail($data['email']);
        empty($data['role'])  ? true : $customer->setRole($data['role']);

        $this->manager->flush();
    }

    public function removeCustomer(Customer $customer)
    {
        $this->manager->remove($customer);
        $this->manager->flush();
    }
}
