<?php

/**
 * Created by PhpStorm.
 * User: sst
 * Date: 08.06.17
 * Time: 10:33
 */
class xlcdLocation extends ActiveRecord
{
    /*
     * sets the parameters for the db. Can be accessed with help of Active Record.
     */
    public static function returnDbTableName()
    {
        return "xlcd_location";
    }

    /**
     * @var int
     *
     * @con_is_primary true
     *
     * @con_sequence true
     *
     * @con_is_notnull true
     *
     * @con_is_unique true
     *
     * @con_has_field true
     *
     * @con_fieldtype integer
     *
     * @con_length 8
     */
    protected $id = 0;

    /**
     * @var int
     *
     * @con_is_unique true
     *
     * @con_has_field false
     *
     * @con_fieldtype integer
     *
     * @con_length 8
     *
     *
     */
    protected $location_data_id;


    /**
     * @var string
     *
     * @con_has_field true
     *
     * @con_fieldtype text
     *
     * @con_length 256
     *
     * @con_is_notnull true
     */
    protected $title;
    /**
     * @var string
     *
     * @con_has_field true
     *
     * @con_fieldtype text
     *
     * @con_is_notnull true
     *
     * @con_length 256
     */
    protected $description;
    /**
     * @var float
     *
     * @con_is_unique false
     *
     * @con_has_field true
     *
     * @con_fieldtype float
     *
     * @con_is_notnull true
     *
     * @con_length 8
     */
    protected $latitude;
    /**
     * @var float
     *
     * @con_is_unique false
     *
     * @con_has_field true
     *
     * @con_fieldtype float
     *
     * @con_is_notnull true
     *
     * @con_length 8
     */
    protected $longitude;
    /**
     * @var DateTime
     *
     * @con_is_unique false
     *
     * @con_has_field true
     *
     * @con_fieldtype date
     *
     * @con_length 8
     *
     * @con_is_notnull true
     */
    protected $creation_date;
    /**
     * @var DateTime
     *
     * @con_is_unique false
     *
     * @con_has_field true
     *
     * @con_fieldtype date
     *
     * @con_length 8
     */
    protected $update_date;
    /**
     * @var integer
     *
     * @con_is_unique false
     *
     * @con_has_field true
     *
     * @con_fieldtype integer
     *
     * @con_length 8
     */
    protected $creator_user_id;

    /**
     * @var integer
     *
     * @con_is_unique false
     *
     * @con_has_field true
     *
     * @con_fieldtype integer
     *
     * @con_length 8
     */
    protected $updater_user_id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getLocationDataId()
    {
        return $this->location_data_id;
    }

    /**
     * @param int $location_data_id
     */
    public function setLocationDataId($location_data_id)
    {
        $this->location_data_id = $location_data_id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * @param DateTime $creation_date
     */
    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;
    }

    /**
     * @return DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * @param DateTime $update_date
     */
    public function setUpdateDate($update_date)
    {
        $this->update_date = $update_date;
    }

    /**
     * @return int
     */
    public function getCreatorUserId()
    {
        return $this->creator_user_id;
    }

    /**
     * @param int $creator_user_id
     */
    public function setCreatorUserId($creator_user_id)
    {
        $this->creator_user_id = $creator_user_id;
    }

    /**
     * @return int
     */
    public function getUpdaterUserId()
    {
        return $this->updater_user_id;
    }

    /**
     * @param int $updater_user_id
     */
    public function setUpdaterUserId($updater_user_id)
    {
        $this->updater_user_id = $updater_user_id;
    }

}