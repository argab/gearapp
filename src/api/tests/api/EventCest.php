<?php
namespace api\tests;
use common\dictionaries\Role;
use Faker\Factory;

class EventCest
{
    private $user;
    private $profile;

    /** @var Factory */
    private $faker;
    private $eventId;

    public function setup(ApiTester $I)
    {
        $this->user = $I->signip();
        $I->setRole(Role::R_ORGANIZER);
        $this->profile = $I->setProfile(Role::R_ORGANIZER);
        $this->faker = Factory::create();
    }

    public function _before(ApiTester $I)
    {

    }

    public function _after(ApiTester $I)
    {
        d($I->grabResponse());
    }

    public function createEventTest(ApiTester $I)
    {
        $event = $I->fakeEvent($I->sendPhoto()['id']);

        $I->amBearerAuthenticated($this->user['token']);
        $I->sendPOST('/event', $event);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $this->eventId = $I->grabDataFromResponseByJsonPath("$.data.item.id")[0];
    }

    public function updateEventTest(ApiTester $I)
    {
        $event = $I->fakeEvent($I->sendPhoto()['id']);
        $I->amBearerAuthenticated($this->user['token']);
        $I->sendPUT('/event/' . $this->eventId, $event);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function setLikeTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->user['token']);
        $I->sendPOST("/event/$this->eventId/like");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function unsetLikeTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->user['token']);
        $I->sendPOST("/event/$this->eventId/dislike");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function toFavoriteTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->user['token']);
        $I->sendPOST("/event/$this->eventId/to-favorite");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function unFavoriteTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->user['token']);
        $I->sendPOST("/event/$this->eventId/un-favorite");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function deleteEventTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->user['token']);
        $I->sendDELETE('/event/' . $this->eventId);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}
