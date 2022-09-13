<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: PizzaIngredient::class, orphanRemoval: true)]
    private Collection $pizzaIngredients;

    public function __construct()
    {
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

    public function setPrice(string $price): self
    {
        $this->price = $price;

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
            $pizzaIngredient->setIngredient($this);
        }

        return $this;
    }

    public function removePizzaIngredient(PizzaIngredient $pizzaIngredient): self
    {
        if ($this->pizzaIngredients->removeElement($pizzaIngredient)) {
            // set the owning side to null (unless already changed)
            if ($pizzaIngredient->getIngredient() === $this) {
                $pizzaIngredient->setIngredient(null);
            }
        }

        return $this;
    }
}
