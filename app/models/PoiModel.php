<?php
/**
 * Created by PhpStorm.
 * User: Grabe Grabe
 * Date: 12/10/2018
 * Time: 5:44 AM
 */

namespace Aiden\Models;


class PoiModel extends _BaseModel
{
    const TYPE_PRIMARY = 1;
    const TYPE_SECODARY = 2;

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false)
     */
    protected $id;

    /**
     * @Column(type="integer", nullable=false)
     */
    protected $users_id;

    /**
     * @Column(type="string", nullable=false)
     */
    protected $address;

    /**
     * @Column(type="double", nullable=false)
     */
    protected $radius;

    /**
     * @Column(type="double", nullable=false)
     */
    protected $min_cost;

    /**
     * @Column(type="double", nullable=false)
     */
    protected $max_cost;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $metadata;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $type;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $crawled;

    /**
     * @Column(type="string", nullable=true)
     */
    protected $date_created;

    /**
     * Returns the database table name
     * @return string
     */
    public function getSource() {

        return 'poi';

    }

    /**
     * Sets the database relations within the app
     */
    public function initialize() {
        $this->belongsTo('users_id', 'Aiden\Models\Users', 'id', ['alias' => 'User']);
    }


    /**
     * Returns the model's unique identifier
     * @return int
     */
    public function getId() {

        return $this->id;

    }

    /**
     * Returns the related council's unique identifier
     * @return int
     */
    public function getUsersId() {

        return $this->users_id;

    }

    /**
     * Returns the related council's unique identifier
     * @return int
     */
    public function setUsersId(int $users_id) {

        return $this->users_id = $users_id;

    }

    /**
     * Returns the email address
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Returns the email address
     * @return string
     */
    public function setAddress(string $address) {
        return $this->address = $address;
    }

    /**
     * Returns the email address
     * @return double
     */
    public function getMinCost() {
        return $this->min_cost;
    }

    /**
     * Sets the email address
     * @param double $email
     */
    public function setMinCost(string $min_cost) {
        $this->min_cost = $min_cost;
    }

    /**
     * Returns the email address
     * @return double
     */
    public function getMaxCost() {
        return $this->max_cost;
    }

    /**
     * Sets the email address
     * @param double $email
     */
    public function setMaxCost(string $max_cost) {
        $this->max_cost = $max_cost;
    }

    /**
     * Returns the email address
     * @return double
     */
    public function getMetadata() {
        return $this->metadata;
    }

    /**
     * Sets the email address
     * @param double $email
     */
    public function setMetadata(bool $metadata) {
        $this->metadata = $metadata;
    }


    /**
     * Returns the email address
     * @return double
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Sets the email address
     * @param double $email
     */
    public function setType(int $type) {
        $this->type = $type;
    }

    /**
     * Returns the email address
     * @return double
     */
    public function getDateCreated() {
        return $this->date_created;
    }

    /**
     * Sets the email address
     * @param double $email
     */
    public function setDateCreated(string $date_created) {
        $this->date_created = $date_created;
    }




}