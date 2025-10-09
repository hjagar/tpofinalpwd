<?php
namespace PhpMvc\Framework\Security;

use PhpMvc\Framework\Http\Request;

interface AuthorizationInterface
{
    public function check(Request $request): bool;
}