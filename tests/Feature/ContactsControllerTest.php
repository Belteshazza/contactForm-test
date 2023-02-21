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

   
    
}
