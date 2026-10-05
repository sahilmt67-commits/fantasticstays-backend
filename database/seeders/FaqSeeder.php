<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Villa;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $sunset = Villa::where('slug', 'sunset-villa-luxury')->first();

        $faqs = [
            [
                'villa_id' => $sunset?->id,
                'question' => 'What are the standard check-in and check-out timings?',
                'answer' => 'Check-in is from 02:00 PM and check-out is until 11:00 AM. Early check-in or late check-out is subject to availability and prior confirmation with our concierge.',
                'category' => 'Villa Rules',
                'display_order' => 1,
            ],
            [
                'villa_id' => $sunset?->id,
                'question' => 'Is a private chef included with the villa reservation?',
                'answer' => 'Yes, a private chef service can be arranged upon request. Ingredients and groceries are billed at actuals based on your curated menu choices.',
                'category' => 'Services',
                'display_order' => 2,
            ],
            [
                'villa_id' => $sunset?->id,
                'question' => 'Are pets allowed at the villa?',
                'answer' => 'We welcome well-trained pets with advance notice. A small refundable cleaning deposit may apply.',
                'category' => 'Villa Rules',
                'display_order' => 3,
            ],
            [
                'villa_id' => null,
                'question' => 'What is the cancellation and refund policy?',
                'answer' => 'Bookings cancelled up to 14 days before check-in receive a full refund. Cancellations between 7 to 14 days receive a 50% refund.',
                'category' => 'Booking & Payment',
                'display_order' => 4,
            ],
            [
                'villa_id' => null,
                'question' => 'Is there high-speed internet and power backup available?',
                'answer' => 'All Fantastic Stays villas are equipped with dual high-speed fiber Wi-Fi connections and 100% automatic diesel/inverter power backup.',
                'category' => 'General',
                'display_order' => 5,
            ],
        ];

        foreach ($faqs as $item) {
            Faq::create($item);
        }
    }
}
