<?php

namespace Subapp\WebApp\Auth;

/**
 * Interface AuthInterface
 *
 * @package Subapp\Webapp\Auth
 */
interface AuthInterface
{
    
    const AUTH_KEY = self::class;
    
    /**
     * @return boolean
     */
    public function isAuthorized();
    
    /**
     * @param array $credentials
     * @return boolean
     */
    public function authorize(array $credentials);
    
    /**
     * @return void
     */
    public function logout();
    
    /**
     * @return null|UserDataObjectInterface
     */
    public function getAuthorized();
    
    /**
     * @param string $password
     * @return string
     */
    public function hashPassword($password);
    
    /**
     * @param string $password
     * @param string $hash
     * @return string
     */
    public function verifyPassword($password, $hash);
    
}