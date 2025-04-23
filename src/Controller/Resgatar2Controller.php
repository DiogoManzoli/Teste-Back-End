<?php

namespace App\Controller;

use App\Entity\Resgate;
use App\DTO\ResgatarDTO; 
use App\Form\ResgateFormType;
use App\Service\ResgatarService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Resgatar2Controller extends AbstractController
{
    #[Route('/resgatar', name: 'resgatar_investimentos')]
    public function resgatar(Request $request, PaginatorInterface $paginator, ResgatarService $resgatarService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $page = $request->query->getInt('page',1);
        $investimentos = $resgatarService->investimentos();
        $investimentos = $paginator->paginate($investimentos, $page, 4);        
        $forms = [];
    
        // Cria um formulário para cada investimento
        foreach ($investimentos as $investimento) {
            $resgate = new Resgate();
            $form = $this->createForm(ResgateFormType::class, $resgate, [
                'action' => $this->generateUrl('resgatar_investimentos', ['page' => $page]),
            ]);
            $forms[$investimento->getId()] = $form;
        }
    
        $investimentoId = $request->request->get('investimento_id');
        
        if ($investimentoId && isset($forms[$investimentoId])) {
            /** @var \Symfony\Component\Form\FormInterface $form */
            $form = $forms[$investimentoId];
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                
                $resgatarDTO = new ResgatarDTO($investimentoId);
                $investimento = $resgatarService->investimentoEspecifico($resgatarDTO);

                if (!$investimento) {
                    return $this->redirectToRoute('resgatar_investimentos');
                }
                
                $saldoFinalComImposto = $resgatarService->calcularSaldoFinal($resgatarDTO);

                /** @var Resgate $resgate */
                $resgate = $form->getData();
                $resgate->setIdUser($this->getUser());
                $resgate->setProdutoInvestimento($investimento->getProdutoInvestimento());
                $resgate->setSaldoFinalComImposto($saldoFinalComImposto);
                $resgatarDTO->setResgate($resgate);
                $resgatarService->realizarResgate($resgatarDTO);

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