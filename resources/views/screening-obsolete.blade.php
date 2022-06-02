<!DOCTYPE html>
<html lang="en">
<head>
    <title>Case Study: Screening</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1" />

    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/main.css') }}"/>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
</head>

<body>
<div class="wrapper">
    <div class="heading"><h1>Screening</h1></div>

    <div class="form-container">
        <form>
            <div>
                <label for="first-name"><span class="bold">First name</span>
                    <input type="text" name="first-name" id="first-name" value="" required />
                </label>
            </div>

            <div>
                <label for="dob"><span class="bold">Date of Birth</span>
                    <input type="date" name="dob" id="dob" value="1960-01-01" required />
                </label>
                <!-- <fieldset>
                    <legend><span>Date of Birth</span></legend>
                    <ul class="in-line">
                        <li>

                        </li>
                        <li>
                            <label for="dob-year"><span>Year</span><br /><br />
                                <input type="text" name="dob-year" id="dob-year" placeholder="1980" required/>
                            </label>
                        </li>
                        <li>
                            <label for="dob-month"><span>Month</span><br />
                                <input type="range" name="dob-month" id="dob-month" placeholder="01" required/>
                            </label>
                        </li>
                        <li>
                            <label for="dob-day"><span>Day</span><br />
                                <input type="text" name="dob-day" id="dob-day" placeholder="01" required/>
                            </label>
                        </li>
                    </ul>
                </fieldset> -->
            </div>

            <div>
                <fieldset>
                    <legend>
                        <span class="bold">Frequency</span><br />
                        <span class="description">On what basis do you experience migraine?</span>
                    </legend>
                    <ul class="in-line">
                        <li>
                            <label for="frequency-monthly">
                                <input
                                    type="radio"
                                    name="frequency"
                                    id="frequency-monthly"
                                    value="monthly"
                                    onclick="toggleDailyFrequency(false)"
                                />
                                <span>Monthly</span>
                            </label>
                        </li>
                        <li>
                            <label for="frequency-weekly">
                                <input
                                    type="radio"
                                    name="frequency"
                                    id="frequency-weekly"
                                    value="weekly"
                                    onclick="toggleDailyFrequency(false)"
                                />
                                <span>Weekly</span>
                            </label>
                        </li>
                        <li>
                            <label for="frequency-daily">
                                <input
                                    type="radio"
                                    name="frequency"
                                    id="frequency-daily"
                                    value="daily"
                                    onclick="toggleDailyFrequency(true)"
                                />
                                <span>Daily</span>
                            </label>
                        </li>
                    </ul>
                </fieldset>
            </div>

            <div id="daily-frequency-container" class="hidden">
                <fieldset>
                    <legend>
                        <span class="bold">Daily Frequency</span><br />
                        <span class="description">How often, per day, do they happen?</span>
                    </legend>

                    <ul>
                        <li>
                            <label for="daily-one">
                                <input type="radio" name="daily-frequency" id="daily-one" value="1-2"/>
                                <span>1-2</span>
                            </label>
                        </li>
                        <li>
                            <label for="daily-two">
                                <input type="radio" name="daily-frequency" id="daily-two" value="2-3"/>
                                <span>2-3</span>
                            </label>
                        </li>
                        <li>
                            <label for="daily-three">
                                <input type="radio" name="daily-frequency" id="daily-three" value="5+"/>
                                <span>5+</span>
                            </label>
                        </li>
                    </ul>
                </fieldset>
            </div>

            <div id="button-container">
                <div id="submit" onclick="submit()"><span>Submit</span></div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
