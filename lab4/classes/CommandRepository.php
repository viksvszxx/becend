<?php
class CommandRepository {
    private array $categories = [];
    private array $commands = [];
    private int $nextCommandId = 1;
    private int $nextExampleId = 1;

    public function __construct(
        private string $categoriesFile,
        private string $commandsFile,
        private string $examplesFile
    ) {
        $this->loadCategories();
        $this->loadCommands();
        $this->loadExamples();
    }

    private function readCsv(string $file): array {
        $rows = [];
        $handle = fopen($file, 'r');
        if ($handle === false) {
            return $rows;
        }
        fgetcsv($handle, 0, ',', '"', ''); 
        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            if ($row !== [null]) {       
                $rows[] = $row;
            }
        }
        fclose($handle);
        return $rows;
    }

    private function writeCsv(string $file, array $header, array $rows): void {
        $handle = fopen($file, 'w');
        fputcsv($handle, $header, ',', '"', '');
        foreach ($rows as $row) {
            fputcsv($handle, $row, ',', '"', '');
        }
        fclose($handle);
    }


    private function loadCategories(): void {
        foreach ($this->readCsv($this->categoriesFile) as $row) {
            [$id, $name] = $row;
            $this->categories[(int) $id] = new Category((int) $id, $name);
        }
    }

    private function loadCommands(): void {
        foreach ($this->readCsv($this->commandsFile) as $row) {
            [$id, $name, $categoryId, $description, $syntax] = $row;
            $this->commands[(int) $id] = new Command(
                (int) $id, $name, $this->categories[(int) $categoryId],
                $description, $syntax
            );
            $this->nextCommandId = max($this->nextCommandId, (int) $id + 1);
        }
    }

    private function loadExamples(): void {
        foreach ($this->readCsv($this->examplesFile) as $row) {
            [$id, $commandId, $description, $code] = $row;
            if (isset($this->commands[(int) $commandId])) {
                $this->commands[(int) $commandId]->addExample(
                    new Example((int) $id, $description, $code)
                );
            }
            $this->nextExampleId = max($this->nextExampleId, (int) $id + 1);
        }
    }

    private function save(): void {
        $commandRows = [];
        $exampleRows = [];
        foreach ($this->commands as $command) {
            $commandRows[] = [
                $command->getId(), $command->getName(),
                $command->getCategory()->getId(),
                $command->getDescription(), $command->getSyntax(),
            ];
            foreach ($command->getExamples() as $example) {
                $exampleRows[] = [
                    $example->getId(), $command->getId(),
                    $example->getDescription(), $example->getCode(),
                ];
            }
        }
        $this->writeCsv(
            $this->commandsFile,
            ['id', 'name', 'category_id', 'description', 'syntax'],
            $commandRows
        );
        $this->writeCsv(
            $this->examplesFile,
            ['id', 'command_id', 'description', 'code'],
            $exampleRows
        );
    }


    public function getCategories(): array {
        return $this->categories;
    }

    public function findAll(): array {
        return $this->commands;
    }

    public function findById(int $id): ?Command {
        return $this->commands[$id] ?? null;
    }

    public function create(
        string $name, Category $category, string $description, string $syntax
    ): Command {
        $command = new Command(
            $this->nextCommandId++, $name, $category, $description, $syntax
        );
        $this->commands[$command->getId()] = $command;
        $this->save();
        return $command;
    }

    public function addExample(
        int $commandId, string $description, string $code
    ): bool {
        $command = $this->findById($commandId);
        if ($command === null) {
            return false;
        }
        $command->addExample(
            new Example($this->nextExampleId++, $description, $code)
        );
        $this->save();
        return true;
    }

    public function update(int $id, array $data): bool {
        $command = $this->findById($id);
        if ($command === null) {
            return false;
        }
        if (isset($data['name'])) {
            $command->setName($data['name']);
        }
        if (isset($data['category'])) {
            $command->setCategory($data['category']);
        }
        if (isset($data['description'])) {
            $command->setDescription($data['description']);
        }
        if (isset($data['syntax'])) {
            $command->setSyntax($data['syntax']);
        }
        $this->save();
        return true;
    }

    public function delete(int $id): bool {
        if (!isset($this->commands[$id])) {
            return false;
        }
        unset($this->commands[$id]);
        $this->save();
        return true;
    }
}
