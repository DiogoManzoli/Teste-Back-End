<?php

namespace App\Controller;

use App\Entity\Resgate;
use App\Form\ResgateFormType;
use App\Repository\InvestimentoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ResgatarInvestimento extends AbstractController
{
    private InvestimentoRepository $investimentoRepository;
    private EntityManagerInterface $em;
    public function __construct(InvestimentoRepository $investimentoRepository,  EntityManagerInterface $EntityManager )
    {
        $this->investimentoRepository = $investimentoRepository;
        $this->em = $EntityManager;
    }
    
    #[Route('/resgatar/{slug?}', name: 'resgatar_investimentos')]
    public function resgatar(Request $request, $slug = null): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $investimentos = $this->investimentoRepository->findAll(); 
        $forms = [];
    
        // Cria um formulário para cada investimento
        foreach ($investimentos as $investimento) {
            $resgate = new Resgate();
            $form = $this->createForm(ResgateFormType::class, $resgate, [
                'action' => $this->generateUrl('resgatar_investimentos'),
            ]);
            $forms[$investimento->getId()] = $form;
        }
    
        $investimentoId = $request->request->get('investimento_id');
        
        if ($investimentoId && isset($forms[$investimentoId])) {
            /** @var \Symfony\Component\Form\FormInterface $form */
            $form = $forms[$investimentoId];
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $investimento = $this->investimentoRepository->find($investimentoId);
    
                if (!$investimento) {
                    $this->addFlash('error', 'Investimento não encontrado!');
                    return $this->redirectToRoute('resgatar_investimentos');
                }
    
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
                
                /** @var Resgate $resgate */
                $resgate = $form->getData();
                $resgate->setIdUser($this->getUser());
                $resgate->setProdutoInvestimento($investimento->getProdutoInvestimento());
                $resgate->setSaldoFinalComImposto($saldoFinalComImposto);
                
                //dd([$montante, $ganhos, $imposto, $saldoFinalComImposto, $resgate]);
                $this->em->persist($resgate);
                $this->em->remove($investimento);
                $this->em->flush();
    
                $this->addFlash('success', 'Investimento resgatado com sucesso!');
                return $this->redirectToRoute('resgatar_investimentos');
            }
        }
    
        $formViews = [];
        foreach ($forms as $id => $form) {
            $formViews[$id] = $form->createView();
        }
    
        return $this->render('resgatar/index.html.twig', [
            'investimentos' => $investimentos,
            'resgatarInvestimento' => $formViews,
        ]);
    }
    
}