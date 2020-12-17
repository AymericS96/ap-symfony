<?php

namespace App\Controller;

use App\Entity\Category;
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
    public function index()
    {
        return $this->render('base.html.twig');
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
     * @Route("/product/{id}-{slug}", name = "detailProduct")
     *
     * @param integer $id idCategory
     * @return Response
     */
    public function showProduct(ProductRepository $productRepository, $id): Response
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
