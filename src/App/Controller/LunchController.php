<?php

namespace App\Controller;

use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class LunchController extends AbstractController
{
    /**
     * @Route("/lunch", name="lunch", methods={"GET"})
     */
    public function index()
    {

        return $this->json([
            'message' => 'Welcome to lunch recipe'
        ]);
    }

    /**
     * @Route("/lunch", methods={"POST"})
     */
    public function post(Request $request)
    {

        $dateRequest = $request->request->get('date');   

        $ingredientFile = file_get_contents(dirname(dirname(__FILE__)).'/Ingredient/data.json');
        $ingredientData = json_decode($ingredientFile, true)['ingredients'];
        
        $recipeFile =  file_get_contents(dirname(dirname(__FILE__)).'/Recipe/data.json');
        $recipeData = json_decode($recipeFile, true)['recipes'];

        $groupingUseBy = array_group_by($ingredientData, 'use-by', 'best-before');
        
        // convert request date to carbon date format
        $lunchDate = Carbon::parse($dateRequest);

        $item = [];
        foreach (array_keys($groupingUseBy) as $useByDate) {
            $checkDate = Carbon::parse($useByDate);
            
            // get use-by after date paramater (get fresh only)
            if($lunchDate->lt($checkDate)){

                // get best-before
                foreach($groupingUseBy[$useByDate] as $bestBeforeDate){
                    foreach($bestBeforeDate as $getTitle){
                        $item[] = $getTitle['title'];
                    }
                    
                }
            }
        }
        
        $groupingRecipe = array_group_by($recipeData, 'title');
        
        $ing = [];
        $availableIng = [];
        foreach($groupingRecipe as $recipes){
            
            $ing = $recipes[0]['ingredients'];
            $difff = array_diff($ing, $item);
            
            
            if($difff == NULL || $difff == []){
                $availableIng[] = [
                    'title' => $recipes[0]['title'],
                    'ingredients' => $ing
                ];
            }
            
        }
        
        // response suitable lunch
        return $this->json($availableIng);
       

    }

}
