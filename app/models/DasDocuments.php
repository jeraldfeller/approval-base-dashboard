<?php

namespace Aiden\Models;

use Aiden\Models\Users;
use Aiden\Models\DasUsers;

class DasDocuments extends _BaseModel {

    const DOCUMENT_NO_NAME = 1;
    const DOCUMENT_NO_URL = 2;
    const DOCUMENT_ERROR_SAVING = 3;
    const DOCUMENT_EXISTS = 4;
    const DOCUMENT_SAVED = 5;

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false)
     */
    protected $id;
    /**
     * @Column(type="integer", nullable=false)
     */
    protected $das_id;
    /**
     * @Column(type="string", nullable=false)
     */
    protected $name;
    /**
     * @Column(type="string", nullable=false)
     */
    protected $url;
    /**
     * @Column(type="string", nullable=true)
     */
    protected $as3_url;
    /**
     * @Column(type="string", nullable=false)
     */
    protected $date;
    /**
     * @Column(type="boolean", nullable=false)
     */
    protected $as3_processed;

    /**
     * Returns the database table name
     * @return string
     */
    public function getSource() {

        return 'das_documents';

    }

    /**
     * Sets the database relations within the app
     */
    public function initialize() {

        $this->belongsTo('das_id', 'Aiden\Models\Das', 'id', ['alias' => 'Da']);

    }

    /**
     * Returns the model's unique identifier
     * @return int
     */
    public function getId() {

        return $this->id;

    }

    /**
     * Returns the affected development application id
     * @return int
     */
    public function getDasId() {

        return $this->das_id;

    }

    /**
     * Sets the affected development application's id
     * @param int $das_id
     */
    public function setDasId(int $das_id) {

        $this->das_id = $das_id;

    }

    /**
     * Gets the name of the document
     * @return string
     */
    public function getName() {
        return $this->name;

    }

    /**
     * Sets the name of the document
     * @param string $name
     */
    public function setName(string $name) {

        $this->name = $name;

    }

    /**
     * Gets the document URL
     * @return type
     */
    public function getUrl($forceCouncilUrl = false) {

        if (strlen($this->getAs3Url()) > 0 && $forceCouncilUrl === false) {
            return $this->getAs3Url();
        }
        else {
            return $this->url;
        }

    }

    /**
     * Sets the document URL
     * @param string $url
     */
    public function setUrl(string $url) {

        $this->url = $url;

    }

    /**
     * Gets the document's AS3 URL
     * @return type
     */
    public function getAs3Url() {
        return $this->as3_url;

    }

    /**
     * Sets the document's AS3 URL
     * @param string $url
     */
    public function setAs3Url(string $as3_url) {

        $this->as3_url = $as3_url;

    }

    /**
     * Gets the document date
     * @return type
     */
    public function getDate() {

        if ($this->date === null) {
            return null;
        }
        else {

            $date = \DateTime::createFromFormat('Y-m-d', $this->date);
            return $date;
        }

    }

    /**
     * Gets the document date
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date) {

        $this->date = $date->format('Y-m-d H:i:s');

    }

    /**
     * Returns whether a document was uploaded to AS3
     */
    public function getAs3Processed() {
        return (bool) $this->as3_processed;

    }

    /**
     * Sets whether a document was uploaded to AS3
     * @param bool $as3_processed
     */
    public function setAs3Processed(bool $as3_processed) {
        $this->as3_processed = (int) $as3_processed;

    }

    /**
     * Returns whether a document already exists based on its related DA and name
     * @param int $das_id
     * @param string $name
     * @return type
     */
    public static function exists(int $das_id, string $name) {

        // We're not checking URL, because some councils add a timestamp to the URL.
        return self::findFirst([
                    "conditions" => "das_id = :das_id: AND name = :name:",
                    "bind" => [
                        "das_id" => $das_id,
                        "name" => $name
                    ]
                ]) !== false;

    }

    /**
     * Attempts to create a document and returns a status explaining what happened
     * @param int $das_id
     * @param string $name
     * @param string $url
     * @param \DateTime $date
     * @return type
     */
    public static function createIfNotExists(int $das_id, string $name, string $url, \DateTime $date = null) {

        // If document has no name
        if (strlen($name) === 0) {
            return self::DOCUMENT_NO_NAME;
        }

        // If document has no URL
        if (strlen($url) === 0) {
            return self::DOCUMENT_NO_URL;
        }

        if (self::exists($das_id, $name) === true) {
            return self::DOCUMENT_EXISTS;
        }

        $daDocument = new self();
        $daDocument->setDasId($das_id);
        $daDocument->setName($name);
        $daDocument->setUrl($url);

        // Set date if passed
        if ($date !== null && $date !== false) {
            $daDocument->setDate($date);
        }

        if ($daDocument->save()) {
            return self::DOCUMENT_SAVED;
        }
        else {
            return self::DOCUMENT_ERROR_SAVING;
        }

    }

}
