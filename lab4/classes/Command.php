<?php

class Command {
    private array $examples = [];

    public function __construct(
        private int $id,
        private string $name,
        private Category $category,
        private string $description,
        private string $syntax
    ) {}

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCategory(): Category { return $this->category; }
    public function getDescription(): string { return $this->description; }
    public function getSyntax(): string { return $this->syntax; }
    public function getExamples(): array { return $this->examples; }

    public function setName(string $name): void {
        $this->name = $name;
    }
    public function setCategory(Category $category): void {
        $this->category = $category;
    }
    public function setDescription(string $description): void {
        $this->description = $description;
    }
    public function setSyntax(string $syntax): void {
        $this->syntax = $syntax;
    }

    public function addExample(Example $example): void {
        $this->examples[] = $example;
    }

    public function toArray(): array {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'category'    => $this->category->getName(),
            'description' => $this->description,
            'syntax'      => $this->syntax,
            'examples'    => array_map(
                fn(Example $e) => $e->toArray(),
                $this->examples
            ),
        ];
    }

    public function __toString(): string {
        return sprintf(
            '#%d %s [%s] — %s (прикладів: %d)',
            $this->id, $this->name, $this->category,
            $this->description, count($this->examples)
        );
    }
}
