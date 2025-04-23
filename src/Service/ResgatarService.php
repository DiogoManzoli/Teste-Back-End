<?php
namespace App\Service;

use App\DTO\ResgatarDTO;
use App\Repository\InvestimentoRepository;
use Doctrine\ORM\EntityManagerInterface;

class ResgatarService
{
    private InvestimentoRepository $investimentoRepository;
    private EntityManagerInterface $em;
    public function __construct(InvestimentoRepository $investimentoRepository,  EntityManagerInterface $EntityManager )
    {
        $this->investimentoRepository = $investimentoRepository;
        $this->em = $EntityManager;
    }

    public function investimentos(){
        
        return $this->investimentoRepository->findAll(); 
    }
    public function investimentoEspecifico(ResgatarDTO $resgatarDTO){

       return $this->investimentoRepository->find($resgatarDTO->getInvestimentoId());
    }

    public function calcularSaldoFinal(ResgatarDTO $resgatarDTO){

        $investimento = $this->investimentoRepository->find($resgatarDTO->getInvestimentoId());
        $hoje = new \DateTime();
        $dataInvestimento = $investimento->getDataInvestimento();
        $intervalo = $dataInvestimento->diff($hoje);
        $anosPassados = $intervalo->y;
        $meses = $intervalo->m;
        $mesesPassados = ($anosPassados * 12) + $meses;

        $taxa = 0.0052;
        $montante = $investimento->getSaldoInicial() * (1 + $taxa) ** $mesesPassados;
        $ganhos = round($montante - $investimento->getSaldoInicial(), 2);
        
        if ($anosPassados < 1) {
            $imposto = $ganhos * 0.225;
        } elseif ($anosPassados < 2) {
            $imposto = $ganhos * 0.185;
        } else {
            $imposto = $ganhos * 0.15;
        }
        $saldoFinalComImposto = $montante - $imposto;

        return $saldoFinalComImposto;
    }

    public function realizarResgate(ResgatarDTO $resgatarDTO){
        $this->em->persist($resgatarDTO->getResgate());
        $this->em->remove($this->investimentoEspecifico($resgatarDTO));
        $this->em->flush();
    }
}