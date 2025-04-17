<?php

namespace App\DataFixtures;

use App\Entity\ProdutoInvestimento;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = new \DateTime('now'); // ou com data específica
        $produtoInvestimento = new ProdutoInvestimento;
        $produtoInvestimento->setNomeInvestimento('Tesouro Direto')
        ->setDataCriacao($data);

        $manager->persist($produtoInvestimento);
        $manager->flush();

        $data = new \DateTime('now'); // ou com data específica
        $produtoInvestimento = new ProdutoInvestimento;
        $produtoInvestimento->setNomeInvestimento('Letras de Crédito Imobiliário')
        ->setDataCriacao($data);

        $manager->persist($produtoInvestimento);
        $manager->flush();
    }
}
