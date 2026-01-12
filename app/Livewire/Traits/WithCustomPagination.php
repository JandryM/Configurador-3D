<?php

namespace App\Livewire\Traits;

trait WithCustomPagination
{
    public int $page = 1;
    public int $perPage = 10;
    public int $total = 0;

    public function initializePagination($defaultPerPage = 10)
    {
        $this->perPage = $defaultPerPage;
    }

    public function updatingPage()
    {
        // Se puede agregar lógica adicional aquí si es necesario
    }

    public function updatingPerPage()
    {
        $this->page = 1;
    }

    public function goToPage($pageNumber)
    {
        $this->page = max(1, min($pageNumber, $this->getTotalPages()));
    }

    public function nextPage()
    {
        if ($this->page < $this->getTotalPages()) {
            $this->page++;
        }
    }

    public function previousPage()
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function firstPage()
    {
        $this->page = 1;
    }

    public function lastPage()
    {
        $this->page = $this->getTotalPages();
    }

    public function getTotalPages(): int
    {
        return (int) ceil($this->total / $this->perPage);
    }

    public function getFromRecord(): int
    {
        return ($this->page - 1) * $this->perPage + 1;
    }

    public function getToRecord(): int
    {
        return min($this->page * $this->perPage, $this->total);
    }

    public function resetPage()
    {
        $this->page = 1;
    }
}
