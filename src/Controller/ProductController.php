<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/producto/listar", name="listarproductos")
     */
    public function listarProductos(): Response
    {
        $em =$this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'listar' => $product,
        ]);
    }

    /**
     * @Route("/producto/agregar", name="agregarproducto")
     */
    public function create(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, ['attr' => ['id' => 'form']]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $product->setCreatedAt(new \DateTime());
            $product->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $this->addFlash('Exito', $product::REGISTRO_CORRECTO);
            return $this->redirectToRoute('listarproductos');
        }
        return $this->render('product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/producto/{id}", name="verproducto")
     */
    public function show($id, Request $request)
    {
        $product = new Product();
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/producto/editar/{id}", name="editarproducto")
     */
    public function store($id, Request $request)
    {
        $product = new Product();
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class, $product, ['attr' => ['id' => 'form']]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $product->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $this->addFlash('Exito', $product::REGISTRO_CORRECTO);
            return $this->redirectToRoute('listarproductos');
        }
        return $this->render('product/update.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/producto/eliminar/{id}", name="borrarproducto")
     */
    public function delete($id, Request $request)
    {
        $product = new Product();
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class, $product, ['attr' => ['id' => 'form']]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            try {
                $em->remove($product);
                $em->flush();
                $this->addFlash('Exito', $product::REGISTRO_BORRADO);
            } catch (\Exception $e) {
                $this->addFlash('Error', $product::ERROR_ACCION);
            }
            return $this->redirectToRoute('listarproductos');
        }
        return $this->render('product/delete.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

}
