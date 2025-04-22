<?php

namespace App\Controller;

use App\Entity\Investimento;
use App\Form\InvestimentoFormType;
use App\Repository\ProdutoInvestimentoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
final class ProdutoController extends AbstractController
{

    private $em;
    private $produtoInvestimentoRepository;
    public function __construct(EntityManagerInterface $entityManager, ProdutoInvestimentoRepository $ProdutoInvestimentoRepository)
    {
        $this->em = $entityManager;
        $this-> produtoInvestimentoRepository = $ProdutoInvestimentoRepository;
    } 

    #[Route('/investimento', name: 'app_investimento')]
    public function investir(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $produtos = $this->produtoInvestimentoRepository->findAll();

        $forms = [];
        $simulacoes = [];

        foreach ($produtos as $produto) {
            $investimento = new Investimento();
            $investimento->setIdUser($user);
            $investimento->setProdutoInvestimento($produto);

            $form = $this->createForm(InvestimentoFormType::class, $investimento);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if ($form->get('simular_investimento')->isClicked()) {
                    $hoje = new \DateTime();
                    $dados = $form->getData();
                    $dataInvestimento = $dados->getDataInvestimento();
                    $saldoInicial = $dados->getSaldoInicial();
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

                    $saldoFinalComImposto = $montante - $imposto;
                    
                    $this->addFlash('simulacoes', [
                        'saldoFinal' => $saldoFinalComImposto,
                        'ganhos' => $ganhos,
                        'produtoName' => $produto->getNomeInvestimento(),
                        'importo' => $imposto,
                    ]);
                    return $this->redirectToRoute('app_investimento',['simulacoes'=> $simulacoes]);
                }

                if ($form->get('submit')->isClicked()) {
                    $this->em->persist($investimento);
                    $this->em->flush();
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