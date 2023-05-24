<?php

namespace App\Admin\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/produit', name:'app_admin_product_')]
class AdminProductController extends AbstractController
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/liste-produit', name: 'list_product')]
    public function  listProduct(){
        $products = $this->productRepository->findAll();

        return $this->render('admin/product/list_product.html.twig', [
            'products' => $products,
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
            
            $slugify = new Slugify();
            $isExist = $this->productRepository->findOneBy(['id' => $newProduct->getId()]);
            if($isExist){
                $date = $newProduct->getCreatedAt();
            }else{
                $date = new \DateTimeImmutable();
            }
            
            $newProduct
            ->setName($productForm->getData()->getName())
            ->setDescription($productForm->getData()->getDescription())
            ->setPrice($productForm->getData()->getPrice())
            ->setPicture('https://picsum.photos/200/300?random')
            ->setSlug($slugify->slugify($newProduct->getName()))
            ->setCategory($productForm->getData()->getCategory())
            ->setCreatedAt($date);
            if($isExist){
                $newProduct->setModifiedAt(new \DateTimeImmutable());
            }

            $this->productRepository->save($newProduct,true);

            $this->addFlash('success','La produit a bien été ajouté');
            return $this->redirectToRoute('app_admin_product_list_product');
        }

        return $this->render('admin/product/add_product.html.twig', [
            'form' => $productForm->createView(),
        ]);
    }

    #[Route('/remove-product/{id}', name: 'remove')]
    public function  removeProduct($id){
        $product = $this->productRepository->findOneBy(['id' => $id]);
        $this->productRepository->remove($product,true);

        $this->addFlash('success', 'Le produit a bien été supprimer');

        return $this->redirectToRoute('app_admin_product_list_product');
    }

}
