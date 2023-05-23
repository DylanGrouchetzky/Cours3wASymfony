<?php

namespace App\Twig\Runtime;

use App\Repository\MenuRepository;
use Twig\Extension\RuntimeExtensionInterface;

class GlobalExtensionRuntime implements RuntimeExtensionInterface
{
    private $menuRepository;
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    public function getMenu()
    {
        $itemMenu = $this->menuRepository->findAll();
        dd($itemMenu);
        return $itemMenu();
    }
}
