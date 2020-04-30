<?php

namespace RICJTech\Covid19Data\Test;

use Ramsey\Uuid\Uuid;
use RICJTech\Covid19Data\Business;
use RICJTech\Covid19Data\DataDesignTest;
use Faker;
require_once (dirname(__DIR__). "/Test/DataDesignTest.php");
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");


class BusinessTest extends DataDesignTest {
	private $VALIDATE_BUSINESS_ID;
	private $VALID_BUSINESSLNG;
	private $VALID_BUSINESSLAT;
	private $VALID_BUSINESSNAME;
	private $VALID_BUSINESSURL;
	private $VALID_BUSINESSYELPID ;

	public function setUp(): void {
		parent::setUp();
		$faker = Faker\Factory::create();


		$this->VALID_BUSINESSLNG = 35.0844;	//todo - need to go back and generate this through faker potentially.
		$this->VALID_BUSINESSLAT = 106.6504;	//todo - how to generate a random float in php
		$this->VALID_BUSINESSNAME = "Covid Business";	//todo - potentially can pull from faker
		$this->VALID_BUSINESSURL = $faker->url;
		$this->VALID_BUSINESSYELPID = "1234567";	//todo - need to generate this somewhere

	}

	public function testInsertValidateBusinessId(): void {


		$numRows = $this->getConnection()->getRowCount("business");


		/** @var Uuid $businessId */

		$businessId = generateUuidV4()->toString();
		$business = new Business($businessId, $this->VALID_BUSINESSYELPID,$this->VALID_BUSINESSLNG,
			$this->VALID_BUSINESSLAT,$this->VALID_BUSINESSNAME,$this->VALID_BUSINESSURL);
		$business->insert($this->getPDO());


		$numRowsAfterInsert = $this->getConnection()->getRowCount("business");
		self::assertEquals($numRows + 1,$numRowsAfterInsert);


		$pdoBusiness = $business->getBusinessbyBusinessId($this->getPDO(),$business->getBusinessId()->getBytes();
		self::assertEquals($this->VALID_BUSINESSYELPID,$pdoBusiness->get
		self::assertEquals($this->VALID_AVATAR_URL,$pdoBusiness->getProfileAvatarUrl());
		self::assertEquals($this->VALID_ACTIVATION_TOKEN, $pdoProfile->getProfileActivationToken());
		self::assertEquals($this->VALID_PROFILE_EMAIL, $pdoProfile->getProfileEmail());
		self::assertEquals($this->VALID_PROFILE_HASH,$pdoProfile->getProfileHash());
		self::assertEquals($this->VALID_PROFILE_PHONE, $pdoProfile->getProfilePhone());
		self::assertEquals($this->VALID_PROFILE_USERNAME,$pdoProfile->getProfileUsername());


	}

	//Update Testing
	public function testUpdateValidProfile():void{

		$faker = Faker\Factory::create();

		//get count of profile records in database before we run the test
		$numRows = $this->getConnection()->getRowCount("profile");


		//insert a profile record in the db
		$profileId = generateUuidV4()->toString();
		$profile = new Profile($profileId, $this->VALID_CLOUDINARY_ID,$this->VALID_AVATAR_URL,
			$this->VALID_ACTIVATION_TOKEN,$this->VALID_PROFILE_EMAIL,$this->VALID_PROFILE_HASH,$this->VALID_PROFILE_PHONE,
			$this->VALID_PROFILE_USERNAME);

		$profile->insert($this->getPDO());

		//update a value on the record that was just inserted
		$changedProfileUsername = $faker->name;
		$profile->setProfileUsername($changedProfileUsername);
		$profile->update($this->getPDO());

		//check count of profile record in the db after the insert
		$numRowsAfterInsert = $this->getConnection()->getRowCount("profile");
		self::assertEquals($numRows + 1,$numRowsAfterInsert,"update checked record count");

		//get a copy of the record just inserted and validate the values
		//make sure the values that went into the record are the same ones that come out
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(),$profile->getProfileId()->toString());
		self::assertEquals($this->VALID_CLOUDINARY_ID,$pdoProfile->getProfileCloudinaryId());
		self::assertEquals($this->	VALID_AVATAR_URL,$pdoProfile->getProfileAvatarUrl());
		self::assertEquals($this->VALID_ACTIVATION_TOKEN, $pdoProfile->getProfileActivationToken());
		self::assertEquals($this->VALID_PROFILE_EMAIL, $pdoProfile->getProfileEmail());
		self::assertEquals($this->VALID_PROFILE_HASH,$pdoProfile->getProfileHash());
		self::assertEquals($this->VALID_PROFILE_PHONE, $pdoProfile->getProfilePhone());

		//verify that the saved username is the same as the updated username
		self::assertEquals($changedProfileUsername,$pdoProfile->getProfileUsername());

	}

	public function testDeleteValidProfile() : void {
		$faker = Faker\Factory::create();
//get count of Profile records in db before we run the test
		$numRows = $this->getConnection()->getRowCount("profile");

		$insertedRow = 3;

		for($i = 0; $i < $insertedRow; $i++){

			$profileId=generateUuidV4()->toString();
			$profile = new Profile(
				$profileId, $this->VALID_CLOUDINARY_ID, $this->VALID_AVATAR_URL,$this->VALID_ACTIVATION_TOKEN,
				$this->VALID_PROFILE_EMAIL=$faker->email,$this->VALID_PROFILE_HASH,$this->VALID_PROFILE_PHONE=$faker->phoneNumber,
				$this->VALID_PROFILE_USERNAME=$faker->userName);

			$profile->insert($this->getPDO());

		}
//get a copy of the record just updated and validate the values
		// make sure the values that went into the record are the same ones that come out
		$numRowsAfterInsert = $this->getConnection()->getRowCount("profile");
		self::assertEquals($numRows + $insertedRow, $numRowsAfterInsert);

		//now delete the last record we inserted
		$profile->delete($this->getPDO());

		//try to get the last record we inserted. it should not exist.
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(),$profile->getProfileId()->toString());
		//validate that only one record was deleted.
		$numRowsAfterDelete = $this->getConnection()->getRowCount("profile");
		self::assertEquals($numRows + $insertedRow - 1, $numRowsAfterDelete);


	}

	public function testProfileValidateByUsername(): void{
		$faker = Faker\Factory::create();
//get count of Profile records in db before we run the test
		$numRows = $this->getConnection()->getRowCount("profile");

		$profileId=generateUuidV4()->toString();
		$profile = new Profile(
			$profileId, $this->VALID_CLOUDINARY_ID, $this->VALID_AVATAR_URL,$this->VALID_ACTIVATION_TOKEN,
			$this->VALID_PROFILE_EMAIL=$faker->email,$this->VALID_PROFILE_HASH,$this->VALID_PROFILE_PHONE=$faker->phoneNumber,
			$this->VALID_PROFILE_USERNAME=$faker->userName);

		$profile->insert($this->getPDO());
		$profile->getProfileByUsername($this->getPDO(),$profile->getProfileUsername());

		$pdoProfile = Profile::getProfileByUsername($this->getPDO(),$profile->getProfileUsername());
		self::assertEquals($this->VALID_PROFILE_USERNAME, $pdoProfile->getProfileUsername());

	}