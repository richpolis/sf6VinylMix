<?php

namespace App\Test\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsuarioControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/usuario/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Usuario::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Usuario index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'usuario[nombre]' => 'Testing',
            'usuario[email]' => 'Testing',
            'usuario[contrasenia]' => 'Testing',
            'usuario[fechacreacion]' => 'Testing',
            'usuario[fechabaja]' => 'Testing',
            'usuario[activo]' => 'Testing',
            'usuario[rol]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Usuario();
        $fixture->setNombre('My Title');
        $fixture->setEmail('My Title');
        $fixture->setContrasenia('My Title');
        $fixture->setFechacreacion('My Title');
        $fixture->setFechabaja('My Title');
        $fixture->setActivo('My Title');
        $fixture->setRol('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Usuario');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Usuario();
        $fixture->setNombre('Value');
        $fixture->setEmail('Value');
        $fixture->setContrasenia('Value');
        $fixture->setFechacreacion('Value');
        $fixture->setFechabaja('Value');
        $fixture->setActivo('Value');
        $fixture->setRol('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'usuario[nombre]' => 'Something New',
            'usuario[email]' => 'Something New',
            'usuario[contrasenia]' => 'Something New',
            'usuario[fechacreacion]' => 'Something New',
            'usuario[fechabaja]' => 'Something New',
            'usuario[activo]' => 'Something New',
            'usuario[rol]' => 'Something New',
        ]);

        self::assertResponseRedirects('/usuario/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNombre());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getContrasenia());
        self::assertSame('Something New', $fixture[0]->getFechacreacion());
        self::assertSame('Something New', $fixture[0]->getFechabaja());
        self::assertSame('Something New', $fixture[0]->getActivo());
        self::assertSame('Something New', $fixture[0]->getRol());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Usuario();
        $fixture->setNombre('Value');
        $fixture->setEmail('Value');
        $fixture->setContrasenia('Value');
        $fixture->setFechacreacion('Value');
        $fixture->setFechabaja('Value');
        $fixture->setActivo('Value');
        $fixture->setRol('Value');

        $$this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/usuario/');
        self::assertSame(0, $this->repository->count([]));
    }
}
