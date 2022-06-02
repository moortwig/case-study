function toggleDailyFrequency(doShow) {
    const dailyContainer = document.getElementById('daily-frequency-container');
    if (doShow) {
        dailyContainer.classList.remove('hidden');
    } else {
        dailyContainer.classList.add('hidden');
    }
}

/**
 * For simplicity, we presume a few things here such as:
 *    - no checkboxes or multi selects are configured on the form, so js is limited to handling radio buttons
 *    - timezone is UTC
 */
function submit() {
    if (main.formEnabled === false) {
        return;
    }
    main.disableForm();

    // Reset error box
    let feedbackBox = document.getElementById('feedback-message-box');
    feedbackBox.classList.add('hidden');
    feedbackBox.classList.remove('error');

    let messageElement = document.getElementById('message');
    messageElement.textContent = '';



    let inputFields = document.getElementsByTagName('input');
    let inputList = Array.prototype.slice.call(inputFields);

    let formValues = []
    for (let i = 0; i < inputList.length; i++) {
        let field = inputList[i];
        if (field.id === 'study-id') {
            continue;
        }
        if (field.type === 'radio' && !field.checked) {
            continue;
        }

        let fieldData = {
            'value': field.value,
            'inputId': field.dataset.inputId,
            'validationType': field.dataset.validationType,
            'required': field.dataset.required,
            'label': field.dataset.label
        }

        formValues.push(fieldData);
    }

    axios.post('/api/screening', {
        formValues: formValues,
        studyId: document.getElementById('study-id').value
    }).then(function (response) {
        console.log('response', response);
        console.log('response.data', response.data);
        main.enableForm();

        // Hide the form, show what a great success this was!
        let formInnerContainer = document.getElementById('form-inner-container');
        formInnerContainer.classList.add('hidden');

        let successBox = document.getElementById('success-message-box');
        successBox.classList.remove('hidden');
        successBox.classList.add('success');

        let messageElement = document.getElementById('success-message');
        messageElement.textContent = response.data.message;

        let form = document.getElementById('screening-form');
        form.classList.add('hidden');
    }).catch(function (error) {
        main.enableForm();

        let feedbackBox = document.getElementById('feedback-message-box');
        feedbackBox.classList.remove('hidden');
        feedbackBox.classList.add('error');

        let messageElement = document.getElementById('message');
        if (error.response.data.message !== undefined) {
            messageElement.textContent = error.response.data.message;
        } else {
            console.log('Something went wrong, ', error);
        }
    });
}

let main = {
    formEnabled: true,

    init: function() {
        console.log('Ready to serve!');
    },

    disableForm: function () {
        main.formEnabled = false;
    },

    enableForm: function () {
        main.formEnabled = true;
    }
}

main.init()
