<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/categoria/listar", name="listarcategorias")
     */
    public function listarCategorias(): Response
    {
        $em =$this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->buscarTodos();

        return $this->render('category/index.html.twig', [
            'listar' => $category,
        ]);
    }

    /**
     * @Route("/categoria/agregar", name="agregarcategoria")
     */
    public function create(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category, ['attr' => ['id' => 'form']]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category->setCreatedAt(new \DateTime());
            $category->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('Exito', $category::REGISTRO_CORRECTO);
            return $this->redirectToRoute('listarcategorias');
        }
        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/categoria/{id}", name="vercategoria")
     */
    public function show($id, Request $request)
    {
        $category = new Category();
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);
        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/categoria/editar/{id}", name="editarcategoria")
     */
    public function store($id, Request $request)
    {
        $category = new Category();
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class, $category, ['attr' => ['id' => 'form']]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('Exito', $category::REGISTRO_CORRECTO);
            return $this->redirectToRoute('listarcategorias');
        }
        return $this->render('category/update.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/categoria/eliminar/{id}", name="borrarcategoria")
     */
    public function delete($id, Request $request)
    {
        $category = new Category();
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class, $category, ['attr' => ['id' => 'form']]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            try {
                $em->remove($category);
                $em->flush();
                $this->addFlash('Exito', $category::REGISTRO_BORRADO);
            } catch (\Exception $e) {
                $this->addFlash('Error', $category::ERROR_ACCION);
            }
            return $this->redirectToRoute('listarcategorias');
        }
        return $this->render('category/delete.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

}
