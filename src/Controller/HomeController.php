<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/",name="index")
     *  */
    public function index(ProductRepository $productRepository)
    {
        // Lister les produits
        // $em= $this->getDoctrine()->getManager();
        // $products= $em->getRepository(Product::class)->findAll();
        // dd($products);
        $products= $productRepository->findAll();
        return $this->render('index.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/test",name="test")
     *  */
    public function test(ProductRepository $productRepository)
    {
        $products= $productRepository->findAll();
        return $this->render('test.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/product/{id}-{slug}", name = "vueProduit")
     *
     * @param integer $id idCategory
     * @return Response
     */
    public function detailProduit(ProductRepository $productRepository, $id): Response
    {
        $product = $productRepository->find($id);
        return $this->render('product/show.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/success", name="success")
     *
     * @return void
     */
    public function success(){
        return $this->render('success.html.twig');
    }
}
