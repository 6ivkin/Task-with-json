<?php

interface ErrorHandlerInterface
{
    public function setNext(ErrorHandlerInterface $handler): ErrorHandlerInterface;
    public function handle(array $data): ?string;
}