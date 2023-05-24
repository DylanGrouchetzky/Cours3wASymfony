<?php

namespace App\Admin\Controller;

use App\Entity\Menu;
use App\Form\ItemType;
use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/menu', name:'app_admin_menu_')]
class AdminMenuController extends AbstractController
{
    private $menuRepository;
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    #[Route('/liste-menu', name: 'list')]
    public function  listMenu(){
        $itemMenu = $this->menuRepository->findAll();

        return $this->render('admin/itemMenu/list_item.html.twig', [
            'items' => $itemMenu,
        ]);
    }

    #[Route('/ajouter-item/{id}', name: 'add_item', defaults: ['id' => null])]
    public function  addCategory($id,Request $request){
        
        if($id != null){
            $newItem = $this->menuRepository->findOneBy(['id' => $id]);
        }else{
            $newItem = new Menu();
        }
        $itemForm = $this->createForm(ItemType::class, $newItem);
        $itemForm->handleRequest($request);

        if($itemForm->isSubmitted() && $itemForm->isValid()){

            $route = $itemForm->getData()->getRoute();
            $paramRoute = $itemForm->getData()->getParamRoute();
            
            $newItem->setItem($itemForm->getData()->getItem());
            if(!empty($route)){
                $newItem->setRoute($route);
            }
            if(!empty($paramRoute)){
                $newItem->setRoute($paramRoute);
            }

            $this->menuRepository->save($newItem,true);
            $this->addFlash('success','L\'item a bien été ajouté');
            return $this->redirectToRoute('app_admin_menu_list');

        }
        return $this->render('admin/itemMenu/add_item.html.twig', [
            'form' => $itemForm->createView(),
        ]);
    }

    #[Route('/category-remove/{id}', name: 'remove')]
    public function  removeCategory($id){
        $item = $this->menuRepository->findOneBy(['id' => $id]);
        
        $this->menuRepository->remove($item, true);
        $this->addFlash('success','L\'item a bien été supprimé');
        return $this->redirectToRoute('app_admin_menu_list');
    }
}
