<?php

namespace Tests\Unit\Api\Studies\Requirements;

use App\Api\Studies\Requirements\AgeRequirementNotMetException;
use App\Api\Studies\Requirements\StudyRequirementService;
use App\Api\Studies\Requirements\StudyRequirementValidator;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StudyRequirementServiceTest extends TestCase
{
    /** @var StudyRequirementValidator|MockObject */
    private $validator;

    private ?StudyRequirementService $service;

    public function setUp(): void
    {
        $this->validator = $this->createMock(StudyRequirementValidator::class);
        $this->service = new StudyRequirementService($this->validator);
    }

    public function tearDown(): void
    {
        $this->validator = null;
        $this->service = null;
    }

    public function testValidateCausesExceptionWhenProvidingUnmetRequirementValue()
    {
        $requirements = ['age' => ['rule' => 'min', 'value' => 18]];
        $this->validator
            ->expects($this->once())
            ->method('validateAge')
            ->willThrowException(new AgeRequirementNotMetException());

        $date = Carbon::createFromDate(2010, 12, 10);

        $this->expectException(AgeRequirementNotMetException::class);
        $this->service->validate($requirements, $date);
    }

    public function testValidateExitsEarlyWhenInsufficientData()
    {
        $requirements = ['foo' => ['rule' => 'min', 'value' => 18]];
        $this->validator
            ->expects($this->never())
            ->method('validateAge');

        $dateIsNull = null;
        $date = Carbon::createFromDate(1974, 8, 27);

        $this->assertNull($this->service->validate($requirements, $dateIsNull));
        $this->assertNull($this->service->validate($requirements, $date));
    }

    public function testValidateMatchesRule()
    {
        $requirements = ['age' => ['rule' => 'min', 'value' => 18]];
        $this->validator
            ->expects($this->once())
            ->method('validateAge');

        $date = Carbon::createFromDate(1974, 8, 27);

        $this->assertNull($this->service->validate($requirements, $date));
    }
}
