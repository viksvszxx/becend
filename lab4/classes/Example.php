<?php
class Example {
    public function __construct(
        private int $id,
        private string $description,
        private string $code
    ) {}

    public function getId(): int { return $this->id; }
    public function getDescription(): string { return $this->description; }
    public function getCode(): string { return $this->code; }

    public function toArray(): array {
        return [
            'description' => $this->description,
            'code'        => $this->code,
        ];
    }

    public function __toString(): string {
        return "{$this->description}: {$this->code}";
    }
}
