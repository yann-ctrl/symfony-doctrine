<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/user/{id}", requirements={"id": "\d+"})
     */
    public function getOneUser($id)
    {
        // entityManager : gestionnaire d'entités de Doctrine
        $manager = $this->getDoctrine()->getManager();

        /**
         * User::class : 'App\entity\User'
         * Retourne un objet User dont les attributs sont settés à partir de la table user en BDD,
         * pour l'utilisateur dont l'id est celui passé en 2eme paramètre
         */
        $user = $manager->find(User::class, $id);

        dump($user);

        /**
           * En verion longue :
           * $repository contient une instance de app\Repository\UserRepository
           $repository = $manager->getRepository(User::class);
           $user = $repository->find($id);
           */

        if (is_null($user)) {
            //retourne une 404
            throw new NotFoundHttpException();
        }

        if(is_null($user)) {
            // retourne une 404
            throw new NotFoundHttpException();
        }

        return $this->render(
            'index/get_one_user.html.twig',
            [
                'user'=> $user
        ]
        );
    }



/**
 * On récupère l'Entity Manager par injection de paramètre dans la méthode
 *
 * @Route("/list-users")
 */
public function listUsers(EntityManagerInterface $manager)
{
    $repository = $manager->getRepository(User::class);

    // retourne tous les utilisateurs de la table user
    // sous la forme d'un tableau d'objets User

    $users = $repository->findAll();

    dump($users);

    return $this->render('index/list_user.html.twig',
        [
            'users' => $users
        ]);
}

    /**
     * @Route("/search-email")
     */
    public function searchEmail(Request $request, UserRepository $repository)
    {
        $twigVars = [];

        if ($request->query->has('email')) {
            $user = $repository->findOneBy([
                'email' => $request->query->get('email')
            ]);

            $twigVars['user'] = $user;

        }

        return $this->render('index/search_email.html.twig',
            [
                $twigVars
            ]);
    }






}
