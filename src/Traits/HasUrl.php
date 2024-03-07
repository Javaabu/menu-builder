<?php

namespace Javaabu\MenuBuilder\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasUrl
{
    protected ?string $url = null;

    public function url(string $url): self
    {
        $this->clearLink();

        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function hasUrl(): bool
    {
        return ! empty($this->getUrl());
    }

    protected function checkActiveFromUrl(): bool
    {
        return $this->getCurrentUrl() == $this->getUrl();
    }

    protected function getCurrentUrl(): ?string
    {
        return request()->fullUrl();
    }

    protected function clearUrl(): self
    {
        $this->url = null;

        return $this;
    }

    protected function generateUrlLink(): string
    {
        return $this->getUrl();
    }
}
