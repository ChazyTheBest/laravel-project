<?php declare(strict_types=1);

namespace App\Traits;

trait WithTable
{
    public int $perPage = 10;
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    protected array $queryString = ['sortField', 'sortDirection'];

    public function sortBy(string $field): void
    {
        $this->sortDirection = $this->sortField === $field && $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->sortField = $field;
    }
}
