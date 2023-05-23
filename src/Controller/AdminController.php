<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\MenuRepository;
use App\Repository\ProductRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name:'app_admin_')]
class AdminController extends AbstractController
{
    private $productRepository;
    private $categoryRepository;
    private $menuRepository;
    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository,MenuRepository $menuRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->menuRepository = $menuRepository;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/liste-item-menu', name: 'list_item_menu')]
    public function  listItemMenu(){
        $items = $this->menuRepository->findAll();

        return $this->render('admin/itemMenu/list_item.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route('/liste-produit', name: 'list_product')]
    public function  listProduct(){
        $products = $this->productRepository->findAll();

        return $this->render('admin/product/list_product.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/liste-categorie', name: 'list_category')]
    public function  listCategory(){
        $categories = $this->categoryRepository->findAll();

        return $this->render('admin/category/list_category.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/ajouter-produit/{id}', name: 'add_product', defaults: ['id' => null])]
    public function  addProduct($id,Request $request){

        if($id != null){
            $newProduct = $this->productRepository->findOneBy(['id' => $id]);
        }else{
            $newProduct = new Product();
        }
        $productForm = $this->createForm(ProductType::class, $newProduct);

        $productForm->handleRequest($request);

        if($productForm->isSubmitted() && $productForm->isValid()){
            
            $date = new \DateTimeImmutable();
            $slugify = new Slugify();
            $newProduct
            ->setName($productForm->getData()->getName())
            ->setDescription($productForm->getData()->getDescription())
            ->setPrice($productForm->getData()->getPrice())
            ->setPicture('https://picsum.photos/200/300?random')
            ->setSlug($slugify->slugify($newProduct->getName()))
            ->setCreatedAt($date)
            ->setCategory($productForm->getData()->getCategory());

            $this->productRepository->save($newProduct,true);

            $this->addFlash('success','La produit a bien été ajouté');
            return $this->redirectToRoute('app_admin_list_product');

        }

        return $this->render('admin/product/add_product.html.twig', [
            'form' => $productForm->createView(),
        ]);
    }

    #[Route('/ajouter-catégorie/{id}', name: 'add_category', defaults: ['id' => null])]
    public function  addCategory($id,Request $request){

        
        if($id != null){
            $newCategory = $this->categoryRepository->findOneBy(['id' => $id]);
        }else{
            $newCategory = new Category();
        }
        $categoryFom = $this->createForm(CategoryType::class, $newCategory);

        $categoryFom->handleRequest($request);

        if($categoryFom->isSubmitted() && $categoryFom->isValid()){
            
            $date = new \DateTimeImmutable();
            $slugify = new Slugify();
            $newCategory
            ->setName($categoryFom->getData()->getName())
            ->setDescription($categoryFom->getData()->getDescription())
            ->setPicture('https://picsum.photos/200/300?random')
            ->setSlug($slugify->slugify($newCategory->getName()));

            $this->categoryRepository->save($newCategory,true);
            $this->addFlash('success','La catégories a bien été ajouté');
            return $this->redirectToRoute('app_admin_list_category');

        }

        return $this->render('admin/category/add_category.html.twig', [
            'form' => $categoryFom->createView(),
        ]);
    }
}
