<?php

namespace ScnSocialAuth\Entity;

class ScnContacts implements ScnContactsInterface {

    protected $userId;
    protected $providerId;
    protected $provider;
    public $first_name;
    public $last_name;
    public $username;
    public $display_name;
    public $email;
    public $id;
    public $location;
    public $home;
    public $company;
    public $job_title;
    public $birthday;

    /**
     * @return the $userId
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param  integer      $userId
     * @return UserProvider
     */
    public function setUserId($userId) {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return the $providerId
     */
    public function getProviderId() {
        return $this->providerId;
    }

    /**
     * @param  integer      $providerId
     * @return UserProvider
     */
    public function setProviderId($providerId) {
        $this->providerId = $providerId;

        return $this;
    }

    /**
     * @return the $provider
     */
    public function getProvider() {
        return $this->provider;
    }

    /**
     * @param  string       $provider
     * @return UserProvider
     */
    public function setProvider($provider) {
        $this->provider = $provider;

        return $this;
    }
    /**
     * @return the $name
     */
    public function getFirstName() {
        return $this->first_name;
    }

    /**
     * @param  string       $name
     * @return UserProvider
     */
    public function setFirstName($name) {
        $this->first_name = $name;

        return $this;
    }
    /**
     * @return the $name
     */
    public function getLastName() {
        return $this->last_name;
    }

    /**
     * @param  string       $name
     * @return UserProvider
     */
    public function setLastName($name) {
        $this->last_name = $name;

        return $this;
    }
    /**
     * @return the $name
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param  string       $name
     * @return UserProvider
     */
    public function setUsername($name) {
        $this->username = $name;

        return $this;
    }
    /**
     * @return the $name
     */
    public function getDisplayName() {
        return $this->display_name;
    }

    /**
     * @param  string       $name
     * @return UserProvider
     */
    public function setDisplayName($name) {
        $this->display_name = $name;

        return $this;
    }
    
     /**
     * @return the $email
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param  string       $email
     * @return UserProvider
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }
    
     /**
     * @return the $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param  string       $id
     * @return UserProvider
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }
    /**
     * @return the $location
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @param  string       $location
     * @return UserProvider
     */
    public function setLocation($location) {
        $this->location = $location;

        return $this;
    }
    /**
     * @return the $home
     */
    public function getHome() {
        return $this->home;
    }

    /**
     * @param  string       $home
     * @return UserProvider
     */
    public function setHome($home) {
        $this->home = $home;

        return $this;
    }
     /**
     * @return the $company
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * @param  string $company
     * @return UserProvider
     */
    public function setCompany($company) {
        $this->company = $company;

        return $this;
    }
     /**
     * @return the $jobTitle
     */
    public function getJobTitle() {
        return $this->job_title;
    }

    /**
     * @param  string $jobTitle
     * @return UserProvider
     */
    public function setJobTitle($jobTitle) {
        $this->job_title = $jobTitle;

        return $this;
    }
     /**
     * @return the $birthday
     */
    public function getBirthday() {
        return $this->birthday;
    }

    /**
     * @param  string $birthday
     * @return UserProvider
     */
    public function setBirthday($birthday) {
        $this->birthday = $birthday;

        return $this;
    }

}
