<?php

namespace App\Api\V1\Screenings;

use App\Api\Studies\Requirements\AgeRequirementNotMetException;
use App\Api\V1\BaseApiController;
use App\Exceptions\MissingCaseException;
use App\Exceptions\MissingDependentDataException;
use App\Exceptions\SQLInsertFailedException;
use App\Exceptions\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class ScreeningController extends BaseApiController
{
    private FormProcessor $formProcessor;
    private FormValidator $formValidator;

    public function __construct(FormProcessor $formProcessor, FormValidator $formValidator)
    {
        $this->formProcessor = $formProcessor;
        $this->formValidator = $formValidator;
    }

    /**
     * We may presume some data is always collected, such as name and date of birth
     * We may also presume the 'participant' is assigned a study group
     * We also presume 'frequency' to always be collected, as part of the study group data
     */
    public function process(Request $request): JsonResponse
    {
        $formValues = $request->get('formValues');
        $studyId = $request->get('studyId');
        if (!$formValues || !$studyId) {
            return $this->badResponse(json_encode(['message' => 'Please fill in the fields']));
        }

        try {
            $this->formValidator->validate($formValues);
        } catch (ValidationException $e) {
            return $this->badResponse(json_encode(['message' => $e->getMessage()]));
        } catch (MissingCaseException|\Throwable $e) {
            $code = Uuid::uuid4();
            Log::error(self::class, [
                'message' => $e->getMessage(),
                'code' => $code,
                'exception' => $e,
            ]);

            return $this->badResponse(json_encode(['message' => sprintf('Something went wrong while processing the submitted form. This error is logged with code \'%s\'', $code)]));
        }

        $formValuesById = $this->formProcessor->mapFormValuesByKey($formValues);
        $date = $this->formProcessor->buildDateFromValues($formValuesById);
        $formattedValues = $this->formProcessor->makeFriendly($formValuesById, $date);

        try {
            $result = $this->formProcessor->process($formattedValues, $studyId);

            return $this->okResponse(json_encode($result));
        } catch (AgeRequirementNotMetException $e) {
            return $this->badResponse(json_encode(['message' => 'Participants must be over 18 years of age']));
        } catch (MissingDependentDataException $e) {
            return $this->badResponse(json_encode(['message' => $e->getMessage()]));
        } catch (SQLInsertFailedException|\Throwable $e) {
            $code = Uuid::uuid4();
            Log::error(self::class, [
                'message' => $e->getMessage(),
                'code' => $code,
                'exception' => $e,
            ]);
            return $this->badResponse(json_encode(['message' => sprintf('Something went wrong while processing the submitted form. This error is logged with code \'%s\'', $code)]));
        }
    }
}
