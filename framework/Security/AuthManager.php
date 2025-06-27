<?php

namespace PhpMvc\Framework\Security;

use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Data\Model;

class AuthManager
{
    private static ?AuthManager $instance = null;

    public function __construct(private readonly Application $app, private readonly string $userAuth = Model::class)
    {
        session_start();
        self::$instance = $this;
    }

    public static function getInstance(): ?AuthManager
    {
        return self::$instance;
    }

    public function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public function user(): ?object
    {
        return $this->check() ? $_SESSION['user'] : null;
    }

    public function getDbUser($primaryKey='id'): ?Model
    {
        $returnValue = null;
        $user = $this->user();

        if ($user && class_exists($this->userAuth)) {
            $userModel = new $this->userAuth();
            $returnValue = $userModel::find($user->$primaryKey);
        }

        return $returnValue;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
    }

    public function attempt(string $userName, string $password, array $authFields = ['email', 'password']): bool
    {
        $returnValue = false;

        if ($this->hasUserCredentials($userName, $password) && class_exists($this->userAuth)) {
            $userModel = new $this->userAuth();
            [$userNameField, $userPasswordField] = $authFields;
            $user = $userModel::where([$userNameField => $userName])->first();
            $userFields = $user->getFields();
            $fieldsToInclude = $this->getFieldsToInclude($userFields, [$userPasswordField]);
            
            if ($user && password_verify($password, $user->$userPasswordField)) {
                $sessionUser = new SessionUser($user, $fieldsToInclude);
                $_SESSION['user'] = $sessionUser;
                $returnValue = true;
            }
        }

        return $returnValue;
    }

    private function hasUserCredentials(string $email, string $password): bool
    {
        $returnValue = true;

        if (empty($email) || empty($password)) {
            return false;
        }

        return $returnValue;
    }

    private function getFieldsToInclude(array $fields, array $excludeFields): array
    {
        $filteredFields = array_filter($fields, fn($field) => !in_array($field, $excludeFields));
  
        return array_values($filteredFields);
    }
}
