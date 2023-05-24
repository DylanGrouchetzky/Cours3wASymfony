<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        shuffle($products);
        return $this->render('front/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/router/{route}',name: 'router')]
    public function router($route){
            return $this->redirectToRoute($route);
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
