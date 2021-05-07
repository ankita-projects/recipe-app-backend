<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\UnicodeString;
use App\Entity\Recipe;




class HomeController extends AbstractController
{

    /**
     * @Route("/recipe/add", name="add_new_recipe")
     */
    public function addRecipe(){
        $entityManager = $this->getDoctrine()->getManager();

        $newRecipe = new Recipe();
        $newRecipe->setName('Paneer');
        $newRecipe->setDifficulty('easy');
        $newRecipe->setDescription('Made with Cheese and vegetables');
        $newRecipe->setImage('url');
        $newRecipe->setType('easy');
        $newRecipe->setLink('url');

        $newRecipe1 = new Recipe();
        $newRecipe1->setName('naan');
        $newRecipe1->setDifficulty('easy');;
        $newRecipe1->setDescription('Made with flour and milk');
        $newRecipe1->setImage('url');
        $newRecipe1->setType('easy');
        $newRecipe1->setLink('url');

        $entityManager->persist($newRecipe);
        $entityManager->persist($newRecipe1);

        $entityManager->flush();

        return new Response('trying to add new recipe...' . $newRecipe1->getId() . $newRecipe->getId());
    }

    /**
     * @Route("/recipe/all", name="get_all_recipe")
     */
    public function getAllRecipe(){
        $recipes = $this->getDoctrine()->getRepository(Recipe::class)->findAll();

        $response = [];

        foreach($recipes as $recipe) {
            $response[] = array(
                'name' => $recipe->getName(),
                'difficulty' => $recipe->getDifficulty(),
                'description'=> $recipe->getDescription(),
                'image'=>$recipe->getImage(),
                'type' => $recipe->getType(),
                'link' => $recipe->getType(),
            );
        }

        return $this->json($response);
    }

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
     * @Route("/recipes/{recipeId}",methods={"GET"}, name="get_recipies_by_id" )
     */
    public function recipieById(Request $request, $recipeId, LoggerInterface $logger): Response
    {
        $rootPath = $this->getParameter('kernel.project_dir');
        $res = file_get_contents($rootPath . '/resources/recipes.json');
        $resjson = json_decode($res, true);
        $response = '';
        foreach ($resjson['recipes'] as $key => $jsons) { // This will search in the 2 jsons
            $logger->info($key);
            if ($jsons['id'] == $recipeId) {
                $response = $jsons;
                break;
            }
        }
        return $this->json($response);
    }

    /**
     * @Route("/search",methods={"GET"}, name="get_recipies_by_name" )
     */
    public function search(Request $request, LoggerInterface $logger): Response
    {
        $rootPath = $this->getParameter('kernel.project_dir');
        $res = file_get_contents($rootPath . '/resources/recipes.json');
        $query = $request->query->get('name');
        $resjson = json_decode($res, true);
        $result = '';
        foreach ($resjson['recipes'] as $key => $jsons) { // This will search in the 2 jsons
            $name = $jsons['name'];
            $content = new UnicodeString($name);
            if ($content->ignoreCase()->startsWith($query)) {
                $result = $jsons;
                break;
            }
        }

        $response = new Response(
            json_encode($result),
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS']
        );
        return $response;
    }
}