<?php

namespace App\Admin\Controller;

use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name:'app_admin_')]
class AdminController extends AbstractController
{
    private $menuRepository;
    public function __construct(MenuRepository $menuRepository)
    {
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
    
}
