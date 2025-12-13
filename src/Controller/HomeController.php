<?php

namespace Watson\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class HomeController {

    /**
     * Home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app) {
        $links = $app['dao.link']->findAll();
        return $app['twig']->render('index.html.twig', array('links' => $links));
    }

    /**
     * Link details controller.
     *
     * @param integer $id Link id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function linkAction($id, Request $request, Application $app) {
        $link = $app['dao.link']->find($id);
        if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
            // A user is fully authenticated : he can add comments
            // Check if he's author for manage link

        }
        return $app['twig']->render('link.html.twig', array(
            'link' => $link
        ));
    }

    /**
     * Links by tag controller.
     *
     * @param integer $id Tag id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function tagAction($id, Request $request, Application $app) {
        $links = $app['dao.link']->findAllByTag($id);
        $tag   = $app['dao.tag']->findTagName($id);

        return $app['twig']->render('result_tag.html.twig', array(
            'links' => $links,
            'tag'   => $tag
        ));
    }

    /**
     * User login controller.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function loginAction(Request $request, Application $app) {
        return $app['twig']->render('login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            )
        );
    }

    //pour le flux rss
     public function rssAction(Application $app) {

        //récup des 15 derniers liens sous forme de tableau en uilisant la méthode crée "findRss" dans LinkDAO
        $links = $app['dao.link']->findRss();

        //formatage pour le flux rss
            $rssXml = '<?xml version="1.0" encoding="UTF-8"?>';
            $rssXml .= '<rss version="2.0">';
            $rssXml .= '<channel>';
            $rssXml .= '<title>Watson – Derniers liens</title>';
            //ici il faut donner dynamquement le lien du site actuel qui héberge le flux
            $rssXml .= '<link>' . $app['request_stack']->getCurrentRequest()->getSchemeAndHttpHost() . '</link>';
            $rssXml .= '<description>Les 15 derniers liens publiés sur Watson</description>';

            // Ajouter chaque lien comme item
            foreach ($links as $link) {
                $rssXml .= '<item>';
                $rssXml .= '<title>' . htmlspecialchars($link->getTitle()) . '</title>';
                $rssXml .= '<link>' . htmlspecialchars($link->getUrl()) . '</link>';
                $rssXml .= '<description>' . htmlspecialchars($link->getDesc()) . '</description>';
                if ($link->getUser()) {
                    $rssXml .= '<author>' . htmlspecialchars($link->getUser()->getUsername()) . '</author>';
                }
                $rssXml .= '<guid>' . htmlspecialchars($link->getId()) . '</guid>';
                $rssXml .= '</item>';
            }

            $rssXml .= '</channel>';
            $rssXml .= '</rss>';
            
            // retourner la résponse
            return new Response($rssXml, 200, ['Content-Type' => 'application/rss+xml']);
        }
}