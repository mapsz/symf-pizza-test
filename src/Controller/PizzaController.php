<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Entity\PizzaIngredient;
use App\Repository\IngredientRepository;
use App\Repository\PizzaIngredientRepository;
use App\Form\PizzaType;
use App\Repository\PizzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pizza')]
class PizzaController extends AbstractController
{
    #[Route('/', name: 'app_pizza_index', methods: ['GET'])]
    public function index(PizzaRepository $pizzaRepository): Response
    {
        return $this->render('pizza/index.html.twig', [
            'pizzas' => $pizzaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pizza_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IngredientRepository $ingredientRepository, PizzaRepository $pizzaRepository, PizzaIngredientRepository $pizzaIngredientRepository): Response
    {
        $pizza = new Pizza();
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //Ingredients

            //Add
            if(isset($_POST['ingredients'])){
                foreach ($_POST['ingredients'] as $ingredientId) {
                    $pizzaIgredient = new PizzaIngredient();
                    $pizzaIgredient->setPosition(is_int($_POST['position_'.$ingredientId]) ? $_POST['position_'.$ingredientId] : null);
                    $pizzaIgredient->setIngredient($ingredientRepository->findOneBy (['id' => $ingredientId])); //todo
                    $pizza->addPizzaIngredient($pizzaIgredient);                    
                    $pizzaIngredientRepository->add($pizzaIgredient, false);
                }
            }
            
            //Pizza
            $pizza->calculatePrice();
            $pizzaRepository->add($pizza, true);

            return $this->redirectToRoute('app_pizza_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pizza/new.html.twig', [
            'pizza' => $pizza,
            'ingredients' => $ingredientRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pizza_show', methods: ['GET'])]
    public function show(Pizza $pizza): Response
    {
        return $this->render('pizza/show.html.twig', [
            'pizza' => $pizza,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pizza_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pizza $pizza, PizzaRepository $pizzaRepository, IngredientRepository $ingredientRepository, PizzaIngredientRepository $pizzaIngredientRepository): Response
    {
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            //Ingredients
            //Remove
            $pizzaIgredientsToDelete = $pizzaIngredientRepository->findBy (['pizza' => $pizza->getId()]);
            foreach ($pizzaIgredientsToDelete as $delete) {
                $pizzaIngredientRepository->remove($delete, false);
            }

            //Add
            if(isset($_POST['ingredients'])){
                foreach ($_POST['ingredients'] as $ingredientId) {
                    $pizzaIgredient = new PizzaIngredient();
                    $pizzaIgredient->setPosition(isset($_POST['position_'.$ingredientId]) ? $_POST['position_'.$ingredientId] : null);
                    $pizzaIgredient->setIngredient($ingredientRepository->findOneBy (['id' => $ingredientId])); //todo
                    $pizza->addPizzaIngredient($pizzaIgredient);                    
                    $pizzaIngredientRepository->add($pizzaIgredient, false);
                }
            }
            
            //Pizza
            $pizza->calculatePrice();
            $pizzaRepository->add($pizza, true);

            return $this->redirectToRoute('app_pizza_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('pizza/edit.html.twig', [
            'pizza' => $pizza,
            'ingredients' => $ingredientRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pizza_delete', methods: ['POST'])]
    public function delete(Request $request, Pizza $pizza, PizzaRepository $pizzaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pizza->getId(), $request->request->get('_token'))) {
            $pizzaRepository->remove($pizza, true);
        }

        return $this->redirectToRoute('app_pizza_index', [], Response::HTTP_SEE_OTHER);
    }
}
