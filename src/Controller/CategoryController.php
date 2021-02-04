<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    private function getData(): array
    {
        /**
         * @var $categoria Category[]
         */
        $list = [];
        $em =$this->getDoctrine()->getManager();
        $categorias = $em->getRepository(Category::class)->buscarTodos();

        foreach ($categorias as $categoria) {
            $activo='Inactivo';
            if($categoria['active'] == 1){
                $activo='Activo';
            }
            $list[] = [
                $categoria['name'],
                $activo
            ];
        }
        return $list;
    }

    /**
     * @Route("/categoria/exportar/xls",  name="exportarxlscategorias")
     */
    public function export()
    {
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
        ];

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Listado de Categorías');
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getCell('A1')->setValue('NOMBRE DE LA CATEGORIA');
        $sheet->getCell('B1')->setValue('ESTADO');

        $sheet->fromArray($this->getData(),null, 'A2', true);

        $writer = new Xlsx($spreadsheet);

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="Categorias.xls"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }

}
