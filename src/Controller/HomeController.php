<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
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
        $rootPath = $this->getParameter('kernel.project_dir');
        $res = file_get_contents($rootPath . '/resources/recipes.json');
        $resjson = json_decode($res, true);
        $response = new Response(
            json_encode($resjson),
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS']
        );
        return $response;
    }

    /**
     * @Route("/recipies/{recipieId}",methods={"GET"}, name="get_recipies_by_id" )
     */
    public function recipieById(Request $request, $recipieId, LoggerInterface $logger): Response
    {
        $rootPath = $this->getParameter('kernel.project_dir');
        $res = file_get_contents($rootPath . '/resources/recipes.json');
        $resjson = json_decode($res, true);
        $response = '';
        foreach ($resjson['recipes'] as $key => $jsons) { // This will search in the 2 jsons
            $logger->info($key);
            if ($jsons['id'] == $recipieId) {
                $response = $jsons;
                break;
            }
        }
        return $this->json($response);
    }
}