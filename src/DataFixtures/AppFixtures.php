<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Customer;
use App\Entity\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadCustomers($manager);
        $this->loadProducts($manager);
    }

    private function loadCustomers(ObjectManager $manager)
    {
        $customer = new Customer();
        $customer->setFirstName('Alex');
        $customer->setLastName('Torres');
        $customer->setDateOfBirth(new \DateTime('1946-09-23 18:57:00'));
        $customer->setCreatedAt(new \DateTime('1946-09-23 18:57:00'));

        $this->addReference('alex_cust', $customer);

        $manager->persist($customer);

        $customer = new Customer();
        $customer->setFirstName('John');
        $customer->setLastName('Smith');
        $customer->setDateOfBirth(new \DateTime('1986-09-23 18:57:00'));
        $customer->setCreatedAt(new \DateTime('1946-09-23 18:58:00'));

        $this->addReference('john_cust', $customer);

        $manager->persist($customer);

        $customer = new Customer();
        $customer->setFirstName('Silvia');
        $customer->setLastName('Murillo');
        $customer->setDateOfBirth(new \DateTime('1976-09-23 18:57:00'));
        $customer->setCreatedAt(new \DateTime('1946-09-23 18:58:00'));

        $this->addReference('silvia_cust', $customer);

        $manager->persist($customer);

        $manager->flush();
    }

    private function loadProducts(ObjectManager $manager)
    {
        $customer = $this->getReference('alex_cust');

        $product = new Product();
        $product->setName('Farm Dron');
        $product->setIssn('1234-abcd');
        $product->setCreatedAt(new \DateTime('1946-09-23 18:57:00'));
        $product->setCustomer($customer);

        $manager->persist($product);

        $customer = $this->getReference('alex_cust');

        $product = new Product();
        $product->setName('Farm Tractor');
        $product->setIssn('1235-abcd');
        $product->setCreatedAt(new \DateTime('1946-09-23 18:58:00'));
        $product->setCustomer($customer);

        $manager->persist($customer);

        $customer = $this->getReference('alex_cust');

        $product = new Product();
        $product->setName('Farm Truck');
        $product->setIssn('1236-abcd');
        $product->setCreatedAt(new \DateTime('1946-09-23 18:59:00'));
        $product->setCustomer($customer);

        $manager->persist($product);

        $customer = $this->getReference('silvia_cust');

        $product = new Product();
        $product->setName('Camera');
        $product->setIssn('1237-abcd');
        $product->setCreatedAt(new \DateTime('1946-09-23 19:58:00'));
        $product->setCustomer($customer);

        $manager->persist($product);

        $customer = $this->getReference('john_cust');

        $product = new Product();
        $product->setName('Nice Book');
        $product->setIssn('1237-abcd');
        $product->setCreatedAt(new \DateTime('1946-09-23 20:58:00'));
        $product->setCustomer($customer);

        $manager->persist($product);

        $manager->flush();
    }
}
