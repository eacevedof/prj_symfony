<?php
// src/Security/Validator/Role/RoleValidator.php
declare(strict_types=1);
namespace App\Security\Validator\Role;

//Interfaz para todos los validadores
use Symfony\Component\HttpFoundation\Request;

interface RoleValidator
{
    public function validate(Request $request): array;
}