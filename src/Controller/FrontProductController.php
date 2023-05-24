<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product', name:'app_product_')]
class FrontProductController extends AbstractController
{
    /*
    #[Route('/liste/{category}', name:'list')]
    public function listProducts(ProductRepository $productRepository, $category): Response
    {
        $products = $productRepository->findBy(['category' => $category]);

        return $this->render('front/pages/listProduct.html.twig',[
            'products' => $products,
        ]);
    }
    */

    #[Route('/product/{slug}', name:'list')]
    public function listProducts(Category $category,ProductRepository $productRepository): Response
    {
        return $this->render('front/pages/listProduct.html.twig',[
            'category' => $category,
        ]);
    }
    /*
    #[Route('/detail/{slug}', name: 'detail')]
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
    */
    #[Route('/detail/{slug}', name: 'detail')]
    public function productDetail(Product $product=null,ProductRepository $productRepository): Response
    {
        if(empty($product)){
            return $this->render('front/pages/404.html.twig');
        }

        return $this->render('front/pages/detail.html.twig', [
            'product' => $product,
        ]);
    }
}
