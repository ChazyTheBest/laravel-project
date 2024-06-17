<?php declare(strict_types=1);

namespace App\Traits;

trait WithModelState
{
    public bool $isModalOpen = false;
    public string $modalType = '';
    public string $modalTitle = '';
    public string $modalContent = '';
    public string $modalCancelMethod = '';
    public string $modalSaveMethod = '';
    public string $modalBtnName = '';

    public function openModal($type, $title, $content, $saveMethod, $cancelMethod, $btnName): void
    {
        $this->modalType = $type;
        $this->modalTitle = $title;
        $this->modalContent = $content;
        $this->modalCancelMethod = $cancelMethod;
        $this->modalSaveMethod = $saveMethod;
        $this->modalBtnName = $btnName;
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->currentModel = null;
        $this->clearFormAttributes();
        $this->resetErrorBag();
    }

    abstract private function clearFormAttributes(): void;
}
