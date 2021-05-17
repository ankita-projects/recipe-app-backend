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
     * @Route("/recipe/add",  methods={"post"}, name="add_new_recipe")
     *
     */
    public function addRecipe(Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(),true);

        $newRecipe = new Recipe();
        $newRecipe->setName($data["name"]);
        $newRecipe->setDifficulty($data["difficulty"]);
        $newRecipe->setDescription($data["description"]);
        $newRecipe->setIngredients($data["ingredients"]);
        $newRecipe->setImage($data["image"]);
        $newRecipe->setType($data["type"]);
        $newRecipe->setLink($data["link"]);

        $entityManager->persist($newRecipe);

        $entityManager->flush();
        $response = new Response();
        $response->setContent('ok');
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    /**
     * @Route("/recipe/all", name="get_all_recipe")
     */
    public function getAllRecipe(){
        $recipes = $this->getDoctrine()->getRepository(Recipe::class)->findAll();

        $response = [];

        foreach($recipes as $recipe) {
            $response[] = array(
                'id' => $recipe->getId(),
                'name' => $recipe->getName(),
                'difficulty' => $recipe->getDifficulty(),
                'description'=> $recipe->getDescription(),
                'ingredients'=> $recipe->getIngredients(),
                'image'=>$recipe->getImage(),
                'type' => $recipe->getType(),
                'link' => $recipe->getType(),
            );
        }

        return $this->json($response);
    }

    /**
     * @Route("/recipe/find/{id}", name="find_a_recipe" )
     */

    public function findRecipe($id)
    {
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->find($id);
        if (!$recipe) {
            throw $this->createNotFoundException(
                'No recipe was found with the id: ' . $id
            );
        } else {
            return $this->json([
                'id' => $recipe->getId(),
                'name' => $recipe->getName(),
                'difficulty' => $recipe->getDifficulty(),
                'description'=> $recipe->getDescription(),
                'ingredients'=> $recipe->getIngredients(),
                'image'=>$recipe->getImage(),
                'type' => $recipe->getType(),
                'link' => $recipe->getType(),
            ]);
        }
    }
        /**
         * @Route("/recipe/edit/{id}/{name}", name="edit_a_recipe")
         */
        public function editRecipe($id, $name) {
            $entityManager = $this->getDoctrine()->getManager();
            $recipe = $this->getDoctrine()->getRepository(Recipe::class)->find($id);

            if (!$recipe) {
                throw $this->createNotFoundException(
                    'No recipe was found with the id: ' . $id
                );
            } else {
                $recipe->setName($name);
                $entityManager->flush();

                return $this->json([
                    'message' => 'Edited a recipe with id ' . $id
                ]);
            }
        }

        /**
         * @Route("/recipe/remove/{id}", name="remove_a_recipe")
         */
        public function removeRecipe($id)
        {
            $entityManager = $this->getDoctrine()->getManager();
            $recipe = $this->getDoctrine()->getRepository(Recipe::class)->find($id);

            if (!$recipe) {
                throw $this->createNotFoundException(
                    'No recipe was found with the id: ' . $id
                );
            } else {
                $entityManager->remove($recipe);
                $entityManager->flush();

                return $this->json([
                    'message' => 'Removed the recipe with id ' . $id
                ]);
            }
        }







    /**
     * @Route("/recipe/findbyname/{name}", name="find_a_recipe_by_name" )
     */

    public function findRecipeByName($name)
    {
        $recipes = $this->getDoctrine()->getRepository(Recipe::class)->findAll();

        $responseJson = [];

        foreach($recipes as $recipe) {
            $responseJson[] = array(
                'id' => $recipe->getId(),
                'name' => $recipe->getName(),
                'difficulty' => $recipe->getDifficulty(),
                'description'=> $recipe->getDescription(),
                'ingredients'=> $recipe->getIngredients(),
                'image'=>$recipe->getImage(),
                'type' => $recipe->getType(),
                'link' => $recipe->getType(),
            );
        }

        $result = '';
        foreach ($responseJson as $key => $jsons) {
            $name1 = $jsons['name'];

            $content = new UnicodeString($name1);
            if ($content->ignoreCase()->startsWith($name)) {
                $result = $jsons;
                break;
            }
        }

        return $this->json($result);
    }
}