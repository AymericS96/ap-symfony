<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{
    protected $em;

    protected $slugger;

    public function __construct(EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $this->em = $em;
        $this->slugger= $slugger;
    }

    public function load(ObjectManager $manager)
    {
        // → Générer 10 produits au hasard
        // Prix au hasard entre 10000 et 20000
        // Un tableau de noms ["Iphone", "Samsung"]
        // Un tableau d'indices [9, 8, etc]
        // Un tableau de fichiers image ['image1', 'image2', etc]

        // Faire une boucle for pour générer des produits dans la table Product avec association au hasard des valeurs

        $category1= $this->em->getRepository(Category::class)->find(1);
        $category2= $this->em->getRepository(Category::class)->find(2);

        for ($i = 0; $i < 8; $i++) {
            $product = new Product();

            $nameArray = ["Iphone 9", "Iphone 10", "Iphone 11", "Iphone 12", "Samsung S9", "Samsung S10", "Samsung S20", "Samsung S21", "Samsung S22", "Samsung S23",];
            $imageArray = ["iphone-7.jpg", "toto2.jpeg", "toto-5fd3977c3c5f1.jpeg", "xiaomi.jpg", "huawei-p20.jpg", "iphone-7.jpg", "toto2.jpeg", "toto-5fd3977c3c5f1.jpeg", "xiaomi.jpg", "huawei-p20.jpg",];

            $randNameIndex = random_int(0, count($nameArray) - 1);
            $name = $nameArray[$randNameIndex];

            $randImageIndex = random_int(0, count($imageArray) - 1);
            $image = $imageArray[$randImageIndex];

            $price = random_int(10000, 20000);
            $slug = $this->slugger->slug($name);

            $category = substr($name, 0, 1) === 'I' ? $category1 : $category2;

            //on enlève les éléments du tableau
            unset($nameArray[$randNameIndex]);
            unset($imageArray[$randImageIndex]);

            $product->setName($name)
                ->setPrice($price)
                ->setSlug($slug)
                ->setImg($image)
                ->setCategory($category);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
