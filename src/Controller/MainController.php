<?php
namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientXMLParserRepository;
use App\Service\ContainerParametersHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/")
     *
     * @return Response
     */
    public function index(): Response {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository(Client::class)->findAll();
//        var_dump($clients[0]->getPhones()[0]); die();
        return $this->render('index.html.twig',[
            'clients' => $clients
        ]);
    }

    /**
     * @Route("/parseXML", name="parseXML")
     *
     * @param ContainerParametersHelper $pathHelpers
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function parseXML (ContainerParametersHelper $pathHelpers) {
        $em = $this->getDoctrine()->getManager();
        $parser = new ClientXMLParserRepository($em,$pathHelpers);
        $parser->parseXML();
        return $this->redirect('/');
    }
}