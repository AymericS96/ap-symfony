<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name= "admin_")
 */
class CategoryController extends AbstractController
{
    /**
     * Liste des catégories
     * @Route("/category", name="category")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $listeCategory = $categoryRepository->findAll();

        return $this->render('category/categoryList.html.twig', [
            'listeCategory' => $listeCategory,
        ]);
    }

    /**
     * @Route("/categoryProduct/{id}", name="categoryProduct")
     */
    public function productByCategory(ProductRepository $productRepository, $id): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $listeProduit = $productRepository->findBy(['Category' => $category]);
        
        return $this->render('category/categoryProducts.html.twig', [
            'listeProduit' => $listeProduit
        ]);
    }

    /**
     * @Route("/category/add", name= "ajoutCategorie")
     *
     * @return void
     */
    public function addCategory(Request $request, EntityManagerInterface $em){
       
        $category = new Category;
        $form= $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajoutée avec succès');

            return $this->redirectToRoute('category');
        }

        return $this->render('category/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/category/edit/{id}", name= "editCategory")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param idCategory $id
     * @return void
     */
    public function editCategory(Request $request, EntityManagerInterface $em, $id){

        $category = $em->getRepository(Category::class)->find($id);
        $form= $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('category/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/category/delete/{id}", name = "deleteCategory")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param id $id
     */
    public function deleteCategory(Category $category, EntityManagerInterface $em, $id)
    {
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('admin_category', []);
    }
}
