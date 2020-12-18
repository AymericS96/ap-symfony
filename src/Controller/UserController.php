<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users", name= "users_")
 */
class UserController extends AbstractController
{
    /**
     * Liste les utilisateurs existants
     * @Route("/list", name= "usersList")
     */
    public function usersList(UserRepository $userrepo)
    {
        $users= $userrepo->findAll();
        // dd($users);
        return $this->render('user/usersList.html.twig', ['users' => $users]);
    }

    /**
     * Ajoute un utilisateur
     * @Route("/add", name= "addUser")
     */
    public function addUser(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder){

        $user= new User;
        $form= $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $user= $form->getData();
            $roles= $form->get('roles')->getData();

            $user->setRoles([0 => $roles]);

            $plainPassword = $form['password']->getData();

            if (trim($plainPassword) != '') {
                //encrypt pass
                $password = $passwordEncoder->encodePassword($user, $plainPassword);
                $user->setPassword($password);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Utilisateur créé avec succès");

            return $this->redirectToRoute('usersList');
        }

        return $this->render('user/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Modifie un utilisateur
     *@Route("/edit/{idUser}", name= "editUser")
     */
    public function editUser(Request $request, User $user, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder){

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            //$role = $request->request->get('user')['roles'];
            $roles = $form->get('roles')->getData();
            $user->setRoles([0 => $roles]);

            $plainPassword = $form['password']->getData();
            if (trim($plainPassword) != '') {
                //encrypt pass
                $password = $passwordEncoder->encodePassword($user, $plainPassword);
                $user->setPassword($password);
            }

            $em->persist($user);
            $em->flush();


            $this->addFlash('success', 'Utilisateur mis à jour avec succès');

            return $this->redirectToRoute('userList', []);
        }

        return $this->render('security/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
