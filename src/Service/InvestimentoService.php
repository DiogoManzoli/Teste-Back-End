<?php

namespace App\Service;

use App\DTO\InvestimentoDTO;
use App\Entity\Investimento;
use App\Repository\ProdutoInvestimentoRepository;
use Doctrine\ORM\EntityManagerInterface;

class InvestimentoService 
{
    private $em;
    private $produtoInvestimentoRepository;
    public function __construct(EntityManagerInterface $entityManager, ProdutoInvestimentoRepository $ProdutoInvestimentoRepository)
    {
        $this-> em = $entityManager;
        $this-> produtoInvestimentoRepository = $ProdutoInvestimentoRepository;
    } 
    public function produtos(): array{

        return $this->produtoInvestimentoRepository->findAll();
    }

    public function simularInvestimento(InvestimentoDTO $investimentoDTO): array{
        
        $hoje = new \DateTime();
        $dataInvestimento = $investimentoDTO->getDataInvestimento();
        $saldoInicial = $investimentoDTO->getSaldoInicial();
        $intervalo = $dataInvestimento->diff($hoje);
        $anosPassados = $intervalo->y;
        $meses = $intervalo->m;
        $mesesPassados = ($anosPassados * 12) + $meses;

        $taxa = 0.0052;
        $montante = $saldoInicial * pow(1 + $taxa, $mesesPassados);
        $ganhos = round($montante - $saldoInicial, 2);

        $imposto = match(true) {
            $anosPassados < 1 => $ganhos * 0.225,
            $anosPassados < 2 => $ganhos * 0.185,
            default => $ganhos * 0.15,
        };

        $saldoFinalSemImposto = round($montante - $imposto, 2);

        return ['saldoFinalSemImposto' =>$saldoFinalSemImposto, 'ganhos'=> $ganhos, 'imposto'=> $imposto];
    }

    public function realizarInvestimento(InvestimentoDTO $investimentoDTO): void{

        $investimento = new Investimento();
        $investimento->setIdUser($investimentoDTO->getUser());
        $investimento->setProdutoInvestimento($investimentoDTO->getProdutoInvestimento());
        $investimento->setSaldoInicial($investimentoDTO->getSaldoInicial());
        $investimento->setDataInvestimento($investimentoDTO->getDataInvestimento());

        $this->em->persist($investimento);
        $this->em->flush();
    }
}