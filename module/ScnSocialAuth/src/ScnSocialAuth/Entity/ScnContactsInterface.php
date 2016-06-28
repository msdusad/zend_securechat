<?php

namespace ScnSocialAuth\Entity;

interface ScnContactsInterface
{
    /**
     * @return the $userId
     */
    public function getUserId();

    /**
     * @param  integer               $userId
     * @return UserProviderInterface
     */
    public function setUserId($userId);

    /**
     * @return the $providerId
     */
    public function getProviderId();

    /**
     * @param  integer               $providerId
     * @return UserProviderInterface
     */
    public function setProviderId($providerId);

    /**
     * @return the $name
     */
    public function getFirstName();

    /**
     * @param  string       $name
     * @return UserProvider
     */
    public function setFirstName($name);
    /**
     * @return the $name
     */
    public function getLastName();

    /**
     * @param  string       $name
     * @return UserProvider
     */
    public function setLastName($name);
    /**
     * @return the $provider
     */
    public function getProvider();

    /**
     * @param  string                $provider
     * @return UserProviderInterface
     */
    public function setProvider($provider);
    
   /**
     * @return the $name
     */
    public function getUsername();

    /**
     * @param  string       $name
     * @return UserProvider
     */
    public function setUsername($name);
    /**
     * @return the $name
     */
    public function getDisplayName();
    /**
     * @param  string       $name
     * @return UserProvider
     */
    public function setDisplayName($name);
    
     /**
     * @return the $email
     */
    public function getEmail();

    /**
     * @param  string       $email
     * @return UserProvider
     */
    public function setEmail($email);
    
     /**
     * @return the $id
     */
    public function getId();

    /**
     * @param  string       $id
     * @return UserProvider
     */
    public function setId($id);
    /**
     * @return the $location
     */
    public function getLocation();
    /**
     * @param  string       $location
     * @return UserProvider
     */
    public function setLocation($location);
    /**
     * @return the $home
     */
    public function getHome();

    /**
     * @param  string       $home
     * @return UserProvider
     */
    public function setHome($home);
     /**
     * @return the $company
     */
    public function getCompany();

    /**
     * @param  string $company
     * @return UserProvider
     */
    public function setCompany($company);
     /**
     * @return the $jobTitle
     */
    public function getJobTitle();

    /**
     * @param  string $jobTitle
     * @return UserProvider
     */
    public function setJobTitle($jobTitle);
     /**
     * @return the $birthday
     */
    public function getBirthday();

    /**
     * @param  string $birthday
     * @return UserProvider
     */
    public function setBirthday($birthday);
}
