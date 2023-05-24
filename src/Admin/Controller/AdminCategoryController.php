<?php

namespace App\Admin\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name:'app_admin_category_')]
class AdminCategoryController extends AbstractController
{
    private $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/liste-categorie', name: 'list_category')]
    public function  listCategory(){
        $categories = $this->categoryRepository->findAll();

        return $this->render('admin/category/list_category.html.twig', [
            'categories' => $categories,
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
            return $this->redirectToRoute('app_admin_category_list_category');

        }

        return $this->render('admin/category/add_category.html.twig', [
            'form' => $categoryFom->createView(),
        ]);
    }

    #[Route('/category-remove/{id}', name: 'remove')]
    public function  removeCategory($id){
        $category = $this->categoryRepository->findOneBy(['id' => $id]);
        $totalProduct = count($category->getProduct());
        if($totalProduct > 0){
            $this->addFlash('error', 'Il reste des produits liée à la catégorie '.$category->getName());
            return $this->redirectToRoute('app_admin_category_list_category');
        }
        $this->categoryRepository->remove($category, true);
        $this->addFlash('success','La catégories a bien été supprimé');
        return $this->redirectToRoute('app_admin_list_category');
    }
}
