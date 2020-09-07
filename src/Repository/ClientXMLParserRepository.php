<?php

namespace App\Repository;
use App\Entity\Client;
use App\Entity\ClientPhone;
use App\Service\ContainerParametersHelper;
use App\Service\PhoneHelper;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\KernelInterface;

class ClientXMLParserRepository extends EntityRepository
{
    private $projectDir;

    public function __construct(ObjectManager $em,ContainerParametersHelper $pathHelpers)
    {
        $this->_em = $em;
        $this->projectDir = $pathHelpers->getApplicationRootDir();
    }

    public function getProjectDir() : string {
        return $this->projectDir;
    }

    public function parseXML() {
        $projectRoot = $this->getProjectDir();
        $xml = file_get_contents($projectRoot."/clients.xml");

        if(!empty($xml)) {
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
                $phones = explode(" ", $properties["numbers"]);

                $entityManager = $this->_em;

                $client_entity = $entityManager->getRepository(Client::class)->findBy(["client_xml_id" => $client_id]);
                if (!$client_entity) {
                    $client_entity = new Client();
                } else {
                    /** @var Client $client_entity */
                    $client_entity = $client_entity[0];
                    $client_entity->removeAllPhones();
                }

                $client_entity->setClientXmlId($client_id);
                $client_entity->setName($properties["name"]);
                $client_entity->setAge($properties["age"]);
                $client_entity->setCity($properties["city"]);
                $client_entity->setMembershipDate(date_create_from_format("Y-m-d H:i:s",
                    $properties["membership_date"]));

                foreach ($phones as $phone) {
                    $entity = new ClientPhone();
                    $phone = PhoneHelper::phoneFormat($phone);
                    $entity->setPhone($phone);
                    $client_entity->addPhone($entity);
                    $entityManager->persist($entity);
                }

                $entityManager->persist($client_entity);
                $entityManager->flush();
            }
        }
    }

}