<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 08.12.14
 * Time: 15:55
 */
namespace Blogger\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testAbout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/about');

        $this->assertEquals(1, $crawler->filter('h1:contains("О symblog")')->count());
    }
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('article.blog')->count() > 0);
//
//        $blogLink = $crawler->filter('article.blog h2 a')->first();
//        $blogTitle = $blogLink->text();
//        $crawler = $client->click($blogLink->link());
//
//        $this->assertEquals(11, $crawler->filter('h2:contains("' . $blogTitle .'")')->count());
    }
    public function testContact()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contact');

        $this->assertEquals(1, $crawler->filter('h1:contains("Symblog контакты")')->count());

        // Select based on button value, or id or name for buttons
        $form = $crawler->selectButton('Отправить')->form();

        $form['contact[name]']       = 'name';
        $form['contact[email]']      = 'email@email.com';
        $form['contact[subject]']    = 'Subject';
        $form['contact[body]']       = 'The comment body must be at least 50 characters long as there is a validation constrain
    on the Enquiry entity';

//        $crawler = $client->submit($form);
//
//
//        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('blogger-notice:contains("Ваш запрос успешно отправлен. Спасибо!")')->count());
    }
}