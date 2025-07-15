<?php

namespace Picobaz\GridView\Contracts;

interface SearchContract
{
    public function fields(): array;
    public function search($query);
    public function exportModel($query);
    public function searchRules(): array;
}