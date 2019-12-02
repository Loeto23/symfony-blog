<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller\Admin
 *
 * @Route("/categorie")
 */
class CategoryController extends AbstractController
{

    /**
     * l'url de la page est /admin/categorie (voir dans config/routes/annotations.yaml)
     * le nom de la route est app_admin_category_index
     *
     * @Route("/")
     */
    public function index(CategoryRepository $repository)
    {
        //$categories = $repository->findAll();
        // équivalent d'un findAll() avec un tri sur l'id
        $categories = $repository->findBy([], ['id' => 'ASC']);

        return $this->render('admin/category/index.html.twig',
            [
                'categories' => $categories
            ]);
    }

    /**
     * Permet d'ajouter une catégorie et de modifier
     * si {id} n'est pas renseigné alors ce sera un ajout, si il l'ai alors ce sera une modification en fonction de l'id
     * {id} est optionnel, vaut null par défaut et doit être un nombre si renseigné
     *
     * @Route("/edition/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     */
    public function edit(Request $request, EntityManagerInterface $manager, $id)
    {
        if(is_null($id)){ // création
            $category = new Category();
        } else { // modification
            $category = $manager->find(Category::class, $id);

            // Erreur 404 si l'id reçu n'est pas en bdd
            if(is_null($category)){
                throw new NotFoundHttpException();
            }
        }

        // création du formulaire relié à la catégorie
        $form = $this->createForm(CategoryType::class, $category);

        // le formulaire analyse la requête
        // et fait le mapping avec l'entité s'il a été soumis
        $form->handleRequest($request);

        dump($category);

        // si le formulaire a été soumis
        if ($form->isSubmitted()) {
            // si les validations à partir des annotations
            // dans l'entity Category sont ok
            if ($form->isValid()) {
                // enregistrement de la catégorie de bdd
                $manager->persist($category);
                $manager->flush();

                // message de confirmation
                $this->addFlash('success', 'La catégorie ' . $category->getName() . ' est enregistrée');

                // redirection vers la page de liste
                return $this->redirectToRoute('app_admin_category_index');
            } else {
                // message d'erreur
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }

        return $this->render('admin/category/edit.html.twig',
            [
                // passage du formulaire au template
                'form' => $form->createView()
            ]);
    }


    /**
     * Permet de supprimer une catégorie
     * Grâce au paramConverter si l'id n'est pas trouvé, une erreur 404 est renvoyé automatiquement
     *
     * @Route("/suppression/{id}", requirements={"id": "\d+"})
     */
    public function delete(EntityManagerInterface $manager, Category $category)
    {
        // suppression de la catégorie en BDD
        $manager->remove($category);
        $manager->flush();

        $this->addFlash('success', 'La catégorie ' . $category->getName() . ' a bien été supprimée');

        return $this->redirectToRoute('app_admin_category_index');
    }


}