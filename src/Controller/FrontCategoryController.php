<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name:'app_category_')]
class FrontCategoryController extends AbstractController
{
    #[Route('/liste', name:'list')]
    public function listCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('front/pages/listCategory.html.twig',[
            'categories' => $categories,
        ]);
    }
}
