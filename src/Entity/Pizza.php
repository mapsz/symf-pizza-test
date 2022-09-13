<?php

namespace App\Entity;

use App\Repository\PizzaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PizzaRepository::class)]
class Pizza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\OneToMany(mappedBy: 'pizza', targetEntity: PizzaIngredient::class)]
    private Collection $Ingredient;

    #[ORM\OneToMany(mappedBy: 'pizza', targetEntity: PizzaIngredient::class, orphanRemoval: true)] 
    private Collection $pizzaIngredients;

    public function __construct()
    {
        $this->Ingredient = new ArrayCollection();
        $this->pizzaIngredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }
    
    public function calculatePrice(): self
    {
        $price = 0;
        foreach ($this->pizzaIngredients as $ingredient) {
            $price += $ingredient->getIngredient()->getPrice();
        }
        $this->price = $price + ($price * 0.50);

        return $this;
    }

    /**
     * @return Collection<int, PizzaIngredient>
     */
    public function getIngredient(): Collection
    {
        return $this->Ingredient;
    }

    public function addIngredient(PizzaIngredient $ingredient): self
    {
        if (!$this->Ingredient->contains($ingredient)) {
            $this->Ingredient->add($ingredient);
            $ingredient->setPizza($this);
        }

        return $this;
    }

    public function removeIngredient(PizzaIngredient $ingredient): self
    {
        if ($this->Ingredient->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getPizza() === $this) {
                $ingredient->setPizza(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PizzaIngredient>
     */
    public function getPizzaIngredients(): Collection
    {
        return $this->pizzaIngredients;
    }

    public function addPizzaIngredient(PizzaIngredient $pizzaIngredient): self
    {
        if (!$this->pizzaIngredients->contains($pizzaIngredient)) {
            $this->pizzaIngredients->add($pizzaIngredient);
            $pizzaIngredient->setPizza($this);
        }

        return $this;
    }

    public function removePizzaIngredient(PizzaIngredient $pizzaIngredient): self
    {
        if ($this->pizzaIngredients->removeElement($pizzaIngredient)) {
            // set the owning side to null (unless already changed)
            if ($pizzaIngredient->getPizza() === $this) {
                $pizzaIngredient->setPizza(null);
            }
        }

        return $this;
    }
}
