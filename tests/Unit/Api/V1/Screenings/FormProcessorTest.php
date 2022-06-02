<?php

namespace Tests\Unit\Api\V1\Screenings;

use App\Api\Studies\Participants\ParticipantService;
use App\Api\Studies\Requirements\StudyRequirementService;
use App\Api\Studies\StudyGroups\StudyGroupService;
use App\Api\Studies\StudyService;
use App\Api\V1\Screenings\FormProcessor;
use App\Api\V1\Screenings\FormValidator;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FormProcessorTest extends TestCase
{
    /** @var StudyRequirementService|MockObject */
    private $requirementService;

    /** @var StudyGroupService|MockObject */
    private $studyGroupService;

    /** @var ParticipantService|MockObject */
    private $participantService;

    /** @var StudyService|MockObject */
    private $studyService;

    private ?FormProcessor $processor;

    public function setUp(): void
    {
        $this->studyService = $this->createMock(StudyService::class);
        $this->requirementService = $this->createMock(StudyRequirementService::class);
        $this->studyGroupService = $this->createMock(StudyGroupService::class);
        $this->participantService = $this->createMock(ParticipantService::class);
        $this->processor = new FormProcessor(
            $this->studyService,
            $this->requirementService,
            $this->studyGroupService,
            $this->participantService
        );
    }

    public function tearDown(): void
    {
        $this->studyService = null;
        $this->requirementService = null;
        $this->studyGroupService = null;
        $this->participantService = null;
        $this->processor = null;
    }

    public function testMapFormValuesByKey()
    {
        $values = [
            [
                'inputId' => 'first-name',
                'required' => true,
                'validationType' => 'name',
                'value' => 'Jane',
                'label' => 'First Name',
            ],
            [
                'inputId' => 'dob-year',
                'required' => true,
                'validationType' => 'year',
                'value' => '1974',
                'label' => 'Year',
            ],
            [
                'inputId' => 'frequency-daily',
                'required' => false,
                'validationType' => 'frequency',
                'value' => 'daily',
                'label' => 'Daily',
            ],
        ];
        $actual = $this->processor->mapFormValuesByKey($values);
        $this->assertArrayHasKey('first-name', $actual);
        $this->assertArrayHasKey('dob-year', $actual);
        $this->assertArrayHasKey('frequency-daily', $actual);
    }

    public function testMakeFriendly()
    {
        $date = Carbon::createFromDate(1974, 8, 27);
        $expected = [
            'firstName' => 'Jane',
            'dateOfBirth' => $date,
            'frequency' => 'daily',
            'dailyFrequency' => '2-3',
        ];

        $actual = $this->processor->makeFriendly($this->getMockedFormValues(), $date);

        $this->assertSame($expected['firstName'], $actual['firstName']);
        $this->assertSame($expected['dateOfBirth']->toDateString(), $actual['dateOfBirth']->toDateString());
        $this->assertSame($expected['frequency'], $actual['frequency']);
        $this->assertSame($expected['dailyFrequency'], $actual['dailyFrequency']);
    }

    public function testBuildDateFromValuesReturnsNullWhenIncompleteDate(): void
    {
        $values = [
            [
                'inputId' => 'dob-year',
                'required' => true,
                'validationType' => 'year',
                'value' => '1974',
                'label' => 'Year',
            ],
            [
                'inputId' => 'dob-month',
                'required' => true,
                'validationType' => 'month',
                'value' => '08',
                'label' => 'Month',
            ],
        ];

        $actual = $this->processor->buildDateFromValues($values);
        $this->assertNull($actual);
    }

    public function testBuildDateFromValues(): void
    {
        $values = [
            [
                'inputId' => 'dob-year',
                'required' => true,
                'validationType' => 'year',
                'value' => '1974',
                'label' => 'Year',
            ],
            [
                'inputId' => 'dob-month',
                'required' => true,
                'validationType' => 'month',
                'value' => '08',
                'label' => 'Month',
            ],
            [
                'inputId' => 'dob-day',
                'required' => true,
                'validationType' => 'day',
                'value' => '27',
                'label' => 'Day',
            ],
        ];

        $actual = $this->processor->buildDateFromValues($values);

        $this->assertInstanceOf(Carbon::class, $actual);
        $this->assertSame('1974-08-27', $actual->format('Y-m-d'));
    }

    private function getMockedFormValues(): array
    {
        return [
            'first-name' => [
                'inputId' => 'first-name',
                'required' => true,
                'validationType' => 'name',
                'value' => 'Jane',
                'label' => 'First Name',
            ],
            'dob-year' => [
                'inputId' => 'dob-year',
                'required' => true,
                'validationType' => 'year',
                'value' => '1974',
                'label' => 'Year',
            ],
            'dob-month' => [
                'inputId' => 'dob-month',
                'required' => true,
                'validationType' => 'month',
                'value' => '08',
                'label' => 'Month',
            ],
            'dob-day' => [
                'inputId' => 'dob-day',
                'required' => true,
                'validationType' => 'day',
                'value' => '27',
                'label' => 'Day',
            ],
            'frequency-daily' => [
                'inputId' => 'frequency-daily',
                'required' => false,
                'validationType' => 'frequency',
                'value' => 'daily',
                'label' => 'Daily',
            ],
            'daily-two' => [
                'inputId' => 'daily-two',
                'required' => false,
                'validationType' => 'daily-frequency',
                'value' => '2-3',
                'label' => '2-3',
            ],
        ];
    }
}
