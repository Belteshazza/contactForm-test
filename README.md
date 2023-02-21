<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## Solution to Coding Test:

Create a RESTFUL API for a contact form that stores its record in the dB
The fields in the form should include:

- a. Name

- b. Email

- c. Attachment

- d. Message

The form should prevent duplicate submission and send email notifications to a value specified in your .env. 
The contact form should allow only PNG, SVG and CSV attachments.
Write unit tests that cover at least one(1) edge case of your choice.
The code should be written and implemented in the Laravel framework

## Solution that covers unit test explained below:

The first test testContactFormSubmissionSuccess covers the scenario where a new form submission is made successfully. It first fakes the email notification by calling the Mail::fake() method. It then creates an array of form data that passes validation and sends a JSON POST request to the /contacts endpoint. It asserts that the response status is 201, the JSON response includes the message "Your submission has been received", the form submission data has been added to the database, and an email notification was sent to the expected address using Laravel's email fake assertion method Mail::assertSent().

The second test testContactFormDuplicateSubmission covers the scenario where a form submission is a duplicate of an existing submission in the database. It creates a Contact model instance and saves it to the database using the Contact::factory()->create() method. It then creates an array of form data that has the same email and message as the existing submission and sends a JSON POST request to the /contacts endpoint. It asserts that the response status is 409 and the JSON response includes the message "This submission has already been received".

The third test testContactFormValidationErrors covers the scenario where the form data fails validation. It creates an array of form data that does not meet the required validation rules and sends a JSON POST request to the /contacts endpoint. It asserts that the response status is 422 and the JSON response includes validation errors for each field.


The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
