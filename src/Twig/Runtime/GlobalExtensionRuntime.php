<?php

namespace App\Twig\Runtime;

use App\Repository\MenuRepository;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class GlobalExtensionRuntime implements RuntimeExtensionInterface
{
    private $menuRepository;
    private $environment;
    public function __construct(MenuRepository $menuRepository, Environment $environment)
    {
        $this->menuRepository = $menuRepository;
        $this->environment = $environment;
    }

    public function getMenu()
    {
        $items = $this->menuRepository->findAll();
        return $this->environment->render('_parts/menu.html.twig', [
            'items' => $items,
        ]);
    }
}
