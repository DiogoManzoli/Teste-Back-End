<?php

namespace App\Controller;

use App\DTO\InvestimentoDTO;
use App\Form\InvestimentoFormType;
use App\Service\InvestimentoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
final class ProdutoController extends AbstractController
{

    #[Route('/investimento', name: 'app_investimento')]
    public function __invoke(Request $request, InvestimentoService $investimentoService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        $produtos = $investimentoService->produtos();
        $forms = [];
        
        foreach ($produtos as $produto) {
            
            $investimentoDTO = new InvestimentoDTO($user, $produto);

            $form = $this->createForm(InvestimentoFormType::class, $investimentoDTO);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                
                if ($form->get('simular_investimento')->isClicked()) {
                    
                    $dados = $form->getData();
                    $investimentoDTO->setSaldoInicial($dados->getSaldoInicial()); 
                    $investimentoDTO->setDataInvestimento($dados->getDataInvestimento());
                    
                    $simularInvesimtento = $investimentoService->simularInvestimento($investimentoDTO);
                    
                    $this->addFlash('simulacoes', [
                        'saldoFinal' => $simularInvesimtento['saldoFinalSemImposto'],
                        'ganhos' => $simularInvesimtento['ganhos'],
                        'produtoName' => $produto->getNomeInvestimento(),
                        'imposto' => $simularInvesimtento['imposto'],
                    ]);

                    return $this->redirectToRoute('app_investimento');
                }

                if ($form->get('submit')->isClicked()) {
                    $dados = $form->getData();
                    $investimentoDTO->setSaldoInicial($dados->getSaldoInicial()); 
                    $investimentoDTO->setDataInvestimento($dados->getDataInvestimento());
                    
                    $investimentoService->realizarInvestimento($investimentoDTO);
                    return $this->redirectToRoute('app_investimento');
                }
            }

            $forms[] = [
                'produto' => $produto,
                'form' => $form->createView(),
            ];
        }

        return $this->render('investimento/index.html.twig', [
            'produtosForm' => $forms
        ]);
    }
}