<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/add", name="ajoutProduit")
     */
    public function addProduct(Request $request, EntityManagerInterface $em): Response
    {

        $builder = $this->createFormBuilder();
        $builder->add('name', TextType::class)
                ->add('price', IntegerType::class)
                ->add('slug', TextType::class)
                ->add(
                    'category', 
                    EntityType::class,
                    [
                        'class' => Category::class,
                        'choice_label' => 'name',
                        'placeholder' => 'Choisir une catégorie',
                        'label' => 'Catégorie',
                    ]
                )
                ->add(
                    'save', 
                    SubmitType::class,
                    ['label' => 'Ajouter Produit']
                );

        $form = $builder->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $product = new Product;
            $product->setName($data['name'])
            ->setPrice($data['price'])
            ->setSlug($data['slug'])
            ->setCategory($data['category']);

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success', []);
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
