<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{

    /**
     * @Route("/admin/product/add", name="ajoutProduit")
     */
    public function addProduct(KernelInterface $appKernel, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        // $path = $appKernel->getProjectDir() . '/public/img';
        $path = $this->getParameter('app.dir.public') . '/img';
        
        $product = new Product;
        $form = $this->createForm(ProductFormType::class, $product);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $product->setSlug($slugger->slug($product->getName()));

            $file = $form['img']->getData();

            if($file){
                // Récupération du nom de fichier sans l'extension
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
               
                $newFileName = $originalFileName . '-' . uniqid() . '-' . 
                
                $file->guessExtension();
                // Set nom dans la propriété img
                $product->setImg($newFileName);

                // Déplacer le fichier dans le répertoire public + sous-répertoire
                try{
                    $file->move(
                        $path, $newFileName
                    );
                }catch (FileException $e){
                    echo $e->getMessage();
                }

            }

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success', []);
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/edit/{id}", name= "editProduct")
     *
     * @param ProductRepository $productRepository
     * @param integer $id idProduct
     * @return Response
     */
    public function editProduct(Request $request, EntityManagerInterface $em, $id): Response
    {
        $path = $this->getParameter('app.dir.public') . '/img';
        
        $product = $em->getRepository(Product::class)->find($id);

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $file = $form['img']->getData();

            if($file){
                // Récupération du nom de fichier sans l'extension
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
               
                $newFileName = $originalFileName . '-' . uniqid() . '-' . 
                
                $file->guessExtension();
                // Set nom dans la propriété img
                $product->setImg($newFileName);

                // Déplacer le fichier dans le répertoire public + sous-répertoire
                try{
                    $file->move(
                        $path, $newFileName
                    );
                }catch (FileException $e){
                    echo $e->getMessage();
                }
            }
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/delete/{id}", name= "deleteProduct")
     *
     * @param ProductRepository $productRepository
     * @param integer $id idProduct
     * @return Response
     */
    public function deleteProduct(Product $product, EntityManagerInterface $em, $id): Response
    {
        // $product = $productRepository->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('success');
    }
}
