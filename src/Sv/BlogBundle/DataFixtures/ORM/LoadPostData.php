<?php
namespace Sv\BlogBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sv\BlogBundle\Entity\Post;
use Sv\BlogBundle\Entity\Tag;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setTitle('First post');
        $post->setText('first');
        $post->setAuthor('Ivanov');
        $post->setCreatedAt(new \DateTime());

        $tag1 = new Tag();
        $tag1->setHashTag("programming");
        $tag1->addPost($post);


        $tag2 = new Tag();
        $tag2->setHashTag("firstPost");
        $tag2->addPost($post);

        $post->addTag($tag1);
        $post->addTag($tag2);


        $manager->persist($post);
        $manager->persist($tag1);
        $manager->persist($tag2);


        $post = new Post();
        $post->setTitle('second post');
        $post->setText('second');
        $post->setAuthor('Petrov');
        $post->setCreatedAt(new \DateTime());


        $tag2 = new Tag();
        $tag2->setHashTag("freelans");
        $tag2->addPost($post);

        $tag1->addPost($post);
        $post->addTag($tag1);
        $post->addTag($tag2);

        $manager->persist($post);
        $manager->persist($tag1);
        $manager->persist($tag2);

        $manager->flush();
    }
} 