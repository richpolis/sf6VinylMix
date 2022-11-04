<?php

namespace App\Test\Controller;

use App\Entity\Promocion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PromocionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/promocion/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Promocion::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Promocion index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'promocion[nombre]' => 'Testing',
            'promocion[emailremitente]' => 'Testing',
            'promocion[asunto]' => 'Testing',
            'promocion[subdominio]' => 'Testing',
            'promocion[activo]' => 'Testing',
            'promocion[fechainicio]' => 'Testing',
            'promocion[fechafinal]' => 'Testing',
            'promocion[fechacreacion]' => 'Testing',
            'promocion[idcliente]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Promocion();
        $fixture->setNombre('My Title');
        $fixture->setEmailremitente('My Title');
        $fixture->setAsunto('My Title');
        $fixture->setSubdominio('My Title');
        $fixture->setActivo('My Title');
        $fixture->setFechainicio('My Title');
        $fixture->setFechafinal('My Title');
        $fixture->setFechacreacion('My Title');
        $fixture->setIdcliente('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Promocion');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Promocion();
        $fixture->setNombre('Value');
        $fixture->setEmailremitente('Value');
        $fixture->setAsunto('Value');
        $fixture->setSubdominio('Value');
        $fixture->setActivo('Value');
        $fixture->setFechainicio('Value');
        $fixture->setFechafinal('Value');
        $fixture->setFechacreacion('Value');
        $fixture->setIdcliente('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'promocion[nombre]' => 'Something New',
            'promocion[emailremitente]' => 'Something New',
            'promocion[asunto]' => 'Something New',
            'promocion[subdominio]' => 'Something New',
            'promocion[activo]' => 'Something New',
            'promocion[fechainicio]' => 'Something New',
            'promocion[fechafinal]' => 'Something New',
            'promocion[fechacreacion]' => 'Something New',
            'promocion[idcliente]' => 'Something New',
        ]);

        self::assertResponseRedirects('/promocion/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNombre());
        self::assertSame('Something New', $fixture[0]->getEmailremitente());
        self::assertSame('Something New', $fixture[0]->getAsunto());
        self::assertSame('Something New', $fixture[0]->getSubdominio());
        self::assertSame('Something New', $fixture[0]->getActivo());
        self::assertSame('Something New', $fixture[0]->getFechainicio());
        self::assertSame('Something New', $fixture[0]->getFechafinal());
        self::assertSame('Something New', $fixture[0]->getFechacreacion());
        self::assertSame('Something New', $fixture[0]->getIdcliente());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Promocion();
        $fixture->setNombre('Value');
        $fixture->setEmailremitente('Value');
        $fixture->setAsunto('Value');
        $fixture->setSubdominio('Value');
        $fixture->setActivo('Value');
        $fixture->setFechainicio('Value');
        $fixture->setFechafinal('Value');
        $fixture->setFechacreacion('Value');
        $fixture->setIdcliente('Value');

        $$this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/promocion/');
        self::assertSame(0, $this->repository->count([]));
    }
}
