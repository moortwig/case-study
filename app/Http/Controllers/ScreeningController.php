<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Ramsey\Uuid\Uuid;

class ScreeningController extends BaseController
{
    private const MOCKED_FIRST_NAME_GROUP_ID = '8fafa07d-9876-4cbd-82c4-cffe726aaeb4';
    private const MOCKED_DOB_GROUP_ID = '1da96d2a-5658-4735-a8d4-15bdf001f1dd';
    private const MOCKED_FREQUENCY_GROUP_ID = 'b5a268a2-11e0-4744-a5f7-0696a059c0ee';
    private const MOCKED_DAILY_FREQUENCY_GROUP_ID = '7b0ad7bf-5de2-4498-9790-2e1ec98ad62c';

    public function getForm()
    {
        // Retrieve data
        $formData = $this->mockedFormData();
        $formGroups = $this->mockedFormGroups();
        $formGroupsMappedById = [];
        foreach ($formGroups as $formGroup) {
            $formGroupsMappedById[$formGroup['id']] = $formGroup;
        }

        // Build form data
        $mappedByGroup = [];
        foreach ($formData as $row) {
            $key = $row['group_id'];
            $mappedByGroup[$key]['fieldData'][] = $row;
            $mappedByGroup[$key]['usesFieldset'] = $row['uses_fieldset'];
            $mappedByGroup[$key]['element_container_id'] = $formGroupsMappedById[$key]['element_container_id'];
            $mappedByGroup[$key]['element_container_className'] = $formGroupsMappedById[$key]['element_container_className'];
            if ($row['uses_fieldset']) {
                $mappedByGroup[$key]['legend'] = $row['legend'];
            }
        }

        $data = [
            'study' => $this->mockedStudyData(),
            'formData' => $mappedByGroup,
        ];

        return view('screening', $data);
    }

    private function mockedStudyData(): array
    {
        return [
                'id' => 1,
                'name' => 'Migraine Study',
                'state' => ucfirst('screening'),
        ];
    }

    private function mockedFormGroups(): array
    {
        return [
            [
                'id' => self::MOCKED_FIRST_NAME_GROUP_ID,
                'element_container_id' => '',
                'element_container_className' => '',
            ],
            [
                'id' => self::MOCKED_DOB_GROUP_ID,
                'element_container_id' => '',
                'element_container_className' => '',
            ],
            [
                'id' => self::MOCKED_FREQUENCY_GROUP_ID,
                'element_container_id' => '',
                'element_container_className' => '',
            ],
            [
                'id' => self::MOCKED_DAILY_FREQUENCY_GROUP_ID,
                'element_container_id' => 'daily-frequency-container',
                'element_container_className' => 'hidden',
            ],
        ];
    }

    private function mockedFormData(): array
    {
        $dobGroupId = self::MOCKED_DOB_GROUP_ID;
        $frequencyGroupId = self::MOCKED_FREQUENCY_GROUP_ID;
        $dailyFrequencyGroupId = self::MOCKED_DAILY_FREQUENCY_GROUP_ID;

        return [
            [
                'id' => 1,
                'study_id' => 1,
                'label' => 'First Name',
                'input_type' => 'text',
                'input_id' => 'first-name',
                'input_name' => 'first-name',
                'value' => '',
                'placeholder' => '',
                'required' => true,
                'uses_fieldset' => false,
                'legend' => null,
                'description' => '',
                'order' => 1,
                'validation_type' => 'name',
                'group_id' => self::MOCKED_FIRST_NAME_GROUP_ID,
            ],
            // Day of Birth fields
            [
                'id' => 2,
                'study_id' => 1,
                'label' => 'Year',
                'input_type' => 'int',
                'input_id' => 'dob-year',
                'input_name' => 'dob-yea',
                'value' => '',
                'placeholder' => '1970',
                'required' => true,
                'uses_fieldset' => true,
                'legend' => 'Date of Birth',
                'description' => '',
                'order' => 2,
                'validation_type' => 'year',
                'group_id' => $dobGroupId,
            ],
            [
                'id' => 3,
                'study_id' => 1,
                'label' => 'Month',
                'input_type' => 'int',
                'input_id' => 'dob-month',
                'input_name' => 'dob-month',
                'value' => '',
                'placeholder' => '01',
                'required' => true,
                'uses_fieldset' => true,
                'legend' => 'Date of Birth',
                'description' => '',
                'order' => 3,
                'validation_type' => 'month',
                'group_id' => $dobGroupId,
            ],
            [
                'id' => 4,
                'study_id' => 1,
                'label' => 'Day',
                'input_type' => 'int',
                'input_id' => 'dob-day',
                'input_name' => 'dob-day',
                'value' => '',
                'placeholder' => '01',
                'required' => true,
                'uses_fieldset' => true,
                'legend' => 'Date of Birth',
                'description' => '',
                'order' => 4,
                'validation_type' => 'day',
                'group_id' => $dobGroupId,
            ],
            // Frequency radio buttons
            [
                'id' => 5,
                'study_id' => 1,
                'label' => 'Monthly',
                'input_type' => 'radio',
                'input_id' => 'frequency-monthly',
                'input_name' => 'frequency',
                'value' => 'monthly',
                'placeholder' => '',
                'required' => false,
                'uses_fieldset' => true,
                'legend' => 'Frequency',
                'description' => 'On what basis do you experience migraine?',
                'onclick' => 'toggleDailyFrequency(false)',
                'order' => 5,
                'validation_type' => 'frequency',
                'group_id' => $frequencyGroupId,
            ],
            [
                'id' => 6,
                'study_id' => 1,
                'label' => 'Weekly',
                'input_type' => 'radio',
                'input_id' => 'frequency-weekly',
                'input_name' => 'frequency',
                'value' => 'weekly',
                'placeholder' => '',
                'required' => false,
                'uses_fieldset' => true,
                'legend' => 'Frequency',
                'description' => 'On what basis do you experience migraine?',
                'onclick' => 'toggleDailyFrequency(false)',
                'order' => 6,
                'validation_type' => 'frequency',
                'group_id' => $frequencyGroupId,
            ],
            [
                'id' => 7,
                'study_id' => 1,
                'label' => 'Daily',
                'input_type' => 'radio',
                'input_id' => 'frequency-daily',
                'input_name' => 'frequency',
                'value' => 'daily',
                'placeholder' => '',
                'required' => false,
                'uses_fieldset' => true,
                'legend' => 'Frequency',
                'description' => 'On what basis do you experience migraine?',
                'onclick' => 'toggleDailyFrequency(true)',
                'order' => 7,
                'validation_type' => 'frequency',
                'group_id' => $frequencyGroupId,
            ],
            // Daily Frequency radio buttons
            [
                'id' => 8,
                'study_id' => 1,
                'label' => '1-2',
                'input_type' => 'radio',
                'input_id' => 'daily-one',
                'input_name' => 'daily-frequency',
                'value' => '1-2',
                'placeholder' => '',
                'required' => false,
                'uses_fieldset' => true,
                'legend' => 'Daily Frequency',
                'description' => 'How often, per day, do they happen?',
                'order' => 8,
                'validation_type' => 'daily-frequency',
                'group_id' => $dailyFrequencyGroupId,
            ],
            [
                'id' => 9,
                'study_id' => 1,
                'label' => '2-3',
                'input_type' => 'radio',
                'input_id' => 'daily-two',
                'input_name' => 'daily-frequency',
                'value' => '2-3',
                'placeholder' => '',
                'required' => false,
                'uses_fieldset' => true,
                'legend' => 'Daily Frequency',
                'description' => 'How often, per day, do they happen?',
                'order' => 9,
                'validation_type' => 'daily-frequency',
                'group_id' => $dailyFrequencyGroupId,
            ],
            [
                'id' => 10,
                'study_id' => 1,
                'label' => '5+',
                'input_type' => 'radio',
                'input_id' => 'daily-three',
                'input_name' => 'daily-frequency',
                'value' => '5+',
                'placeholder' => '',
                'required' => false,
                'uses_fieldset' => true,
                'legend' => 'Daily Frequency',
                'description' => 'How often, per day, do they happen?',
                'order' => 10,
                'validation_type' => 'daily-frequency',
                'group_id' => $dailyFrequencyGroupId,
            ],
        ];
    }
}
