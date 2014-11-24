<?php
namespace Sv\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method as Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template as Template;
use Doctrine\ORM\Query\ResultSetMapping;
use Sv\BlogBundle\Entity\Post;


class PostController extends Controller
{

    /**
     * @Route("/", name="blog_home")
     * @Method({"GET"})
     * @Template("SvBlogBundle:Post:index.html.twig")
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()->getRepository('SvBlogBundle:Post')->findBy([], ['id' => 'DESC']);

        $tags = $this->getDoctrine()->getRepository('SvBlogBundle:Tag')
                                    ->createQueryBuilder('t')
                                    ->groupBy('t.hashTag')
                                    ->setMaxResults(10)
                                    ->getQuery()
                                    ->getResult();




        usort($tags, function($a, $b){
            if(COUNT($a->getPost()) == COUNT($b->getPost())){
                return 0;
            }

            return (COUNT($a->getPost()) > COUNT($b->getPost()))? -1: 1;
        });

        return array(
            "posts" => $posts,
            "tags" => $tags
        );
    }

    /**
     * @Route("/post/add", name="post_add_get")
     * @Method({"GET", "POST"})
     * @Template("SvBlogBundle:Post:add.html.twig")
     */
    public function addPostAction(Request $request)
    {
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();

            $post = new Post();

            $rq = $request->request;

            $post->setTitle($rq->get('title'))
                 ->setText($rq->get('text'))
                 ->setAuthor($rq->get('author'));

            $tags = $rq->get('tags');

            for($i = 0; $i < COUNT($tags); $i++){
                $tag = $this->getDoctrine()->getRepository('SvBlogBundle:Tag')->find($tags[$i]);

                $tag->addPost($post);
                $post->addTag($tag);
            }


            $em->persist($post);
            $em->flush();

            return $this->redirect($this->get('router')->generate('blog_home'));
        }

        return array(
            "tags" => $this->getDoctrine()->getRepository('SvBlogBundle:Tag')->findAll()
        );
    }

    /**
     * @Route("/post/view/{slug}", name="view_post")
     * @Method({"GET"})
     * @Template("SvBlogBundle:Post:view.html.twig")
     */
    public function viewPostAction($slug)
    {
        $post = $this->getDoctrine()->getRepository('SvBlogBundle:Post')->findOneBy(['slugPost' => $slug]);

        return array(
            "post" => $post
        );
    }

    /**
     * @Route("/post/edit/{slug}", name="edit_post")
     * @Method({"GET", "PUT"})
     * @Template("SvBlogBundle:Post:edit.html.twig")
     */
     public function editPostAction($slug, Request $request)
     {

         $post = $this->getDoctrine()->getRepository('SvBlogBundle:Post')->findOneBy(['slug_post' => $slug]);
         $tags = $this->getDoctrine()->getRepository('SvBlogBundle:Tag')->findAll();

         return array(
             "post" => $post,
             "tags" => $tags
         );
     }
}

