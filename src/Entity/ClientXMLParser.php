<?php

namespace App\Entity;
use App\Service\ContainerParametersHelper;
use App\Service\PhoneHelper;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\KernelInterface;

class ClientXMLParser
{
    private $projectDir;

    public function __construct(ContainerParametersHelper $pathHelpers)
    {
        $this->projectDir = $pathHelpers->getApplicationRootDir();
    }

    public function getProjectDir() : string {
        return $this->projectDir;
    }

    public function parseXML() {
        $projectRoot = $this->getProjectDir();
        $xml = file_get_contents($projectRoot."/clients.xml");

        $main_crawler = new Crawler($xml);


        $crawler = $main_crawler->filter('clients client');


        foreach ($crawler as $client) {
            $client_crawler = new Crawler($client);
            $client_id = $client_crawler->attr('id');
            $properties = [];
            foreach ($client_crawler->children() as $client_property) {
                $property_crawler = new Crawler($client_property);
                $properties[$property_crawler->nodeName()] = $property_crawler->text();
            }
            $phones = explode(" ",$properties["numbers"]);

            $entityManager = $this->getDoctrine()->getManager();

            $client = $entityManager->getRepository(Client::class)->findBy(["client_xml_id" => $client_id]);
            if (!$client) {
                $client = new Client();
            }
            $client->setClientXmlId($client_id);
            $client->setName($properties["name"]);
            $client->setAge($properties["age"]);
            $client->setCity($properties["city"]);
            $client->setMembershipDate($properties["membership_date"]);
            foreach ($phones as $phone) {
                $entity = new ClientPhone();
                $phone = PhoneHelper::phoneFormat($phone);
                $entity->setPhone($phone);
            }


            $entityManager->persist($client);
            $entityManager->flush();
        }


        die();
    }

}