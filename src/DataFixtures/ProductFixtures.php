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

        $names= [
            "Iphone 18", 
            "Samsung S45", 
            "Motorola XB48", 
            "Samsung S47",
            "Iphone 20",
            "Iphone 25",
            "Samsung A23",
            "Samsung Galaxy S34",
            "Motorola XB43",
            "Motorola GH56" 
        ];
                    
        $imgs= ["iphone-11.jpg", "motorola-edge.jpg", "motorola-moto-e6.jpg", "samsung-galaxy-a20.jpg", "samsung-galaxy-note-20.jpg", "samsung-galaxy-s20.jpg", "motorola-moto-e6.jpg", "samsung-galaxy-a20.jpg", "samsung-galaxy-note-20.jpg", "samsung-galaxy-s20.jpg"];

        for($i_pdt= 0; $i_pdt < 8; $i_pdt++)
        {
            $product = new Product();
            
            $randNameIndex = random_int(0, count($names) - 1);
            $name = $names[$randNameIndex];

            $randImageIndex = random_int(0, count($imgs) - 1);
            $image = $imgs[$randImageIndex];

            $price = random_int(10000, 20000);

            $slug = $this->slugger->slug($name);
            
            $category= substr($name, 0, 1) === 'I' ? $category2 : $category1;

            // On enlève les éléments du tableau
            unset($names[$randNameIndex]);
            unset($imgs[$randImageIndex]);

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
