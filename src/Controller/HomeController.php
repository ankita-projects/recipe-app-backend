<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\UnicodeString;




class HomeController extends AbstractController
{
    /**
     * @Route("/home",methods={"GET"}, name="index_page" )
     */
    public function home(Request $request): Response
    {
        return new Response('This is my home....');
    }

    /**
     * @Route("/recipes",methods={"GET"}, name="get_all_recipies" )
     */
    public function recipes(Request $request): Response
    {
        return new Response('This is list of recipes home....');
    }
}