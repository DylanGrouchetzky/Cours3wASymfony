<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('front/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/liste-des-catégories', name:'app_list_categories')]
    public function listCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('front/pages/listCategory.html.twig',[
            'categories' => $categories,
        ]);
    }


    #[Route('/liste-des-produits/{category}', name:'app_list_products')]
    public function listProducts(ProductRepository $productRepository, $category): Response
    {
        $products = $productRepository->findBy(['category' => $category]);

        return $this->render('front/pages/listProduct.html.twig',[
            'products' => $products,
        ]);
    }
    
    #[Route('/product/detail/{slug}', name: 'app_product_detail')]
    public function productDetail($slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if(!$product){
            return $this->render('front/pages/404.html.twig');
        }

        return $this->render('front/pages/detail.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/pages/{page}', name: 'app_static_page')]
    public function staticPage($page, Environment $twig): Response
    {
        $template = 'front/pages/'.$page.'.html.twig';
        $loader = $twig->getLoader($template);
        if(!$loader->exists($template)){
            return $this->render('front/pages/404.html.twig');
            throw new NotFoundHttpException();
        }

        return $this->render('front/pages/'.$page.'.html.twig');
    }
}
