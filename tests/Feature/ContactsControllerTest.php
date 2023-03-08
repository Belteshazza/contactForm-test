<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Contact;
use App\Mail\ContactSubmissionNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;


class ContactsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker; 

    /** @test */
    
    public function testContactFormValidationErrors()
    {
        $formData = [
            'name' => '',
            'email' => '',
            'attachment' => '',
            'message' => ''
        ];

        $response = $this->postJson('/api/contacts', $formData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'attachment', 'message']);
    }
    

    /** @test */
    public function it_stores_submission()
    {
        Mail::fake();
        $formData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'attachment' => UploadedFile::fake()->create('document.csv', 1000, 'application/csv'),
            'message' => $this->faker->paragraph,
        ];

        $response = $this->postJson('/api/contacts', $formData);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Your submission has been received']);

        $this->assertDatabaseHas('contacts', [
            'name' => $formData['name'],
            'email' => $formData['email'],
            'attachment' => 'attachments/' . $formData['attachment']->hashName(),
            'message' => $formData['message'],
        ]);

        Mail::assertSent(ContactSubmissionNotification::class, function ($mail) {
            return $mail->hasTo(env('EMAIL_NOTIFICATION_ADDRESS'));
        });
    }

    /** @test */
    public function it_rejects_duplicate_submissions()
    {
        // Create a test submission
        $submission = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'attachment' => UploadedFile::fake()->create('attachment.csv'),
            'message' => 'This is a test message',
        ];

        // Store the submission in the database
        $this->postJson('/api/contacts',  $submission);

        // Attempt to submit the same data again
        $response = $this->postJson('/api/contacts',  $submission);

        // Assert that the response has the correct status code and error message
        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'This submission has already been received',
        ]);

        // Assert that only one submission was stored in the database
        $this->assertCount(1, Contact::all());

       
    }
}
