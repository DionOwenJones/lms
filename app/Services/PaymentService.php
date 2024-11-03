<?php

namespace App\Services;

use App\Models\Payment;

class PaymentService
{
    public function processPurchase(array $data): Payment
    {
        // Implement your payment processing logic here
        // This is just a placeholder
        return Payment::create([
            'amount' => $data['amount'],
            'business_id' => $data['business_id'],
            'course_id' => $data['course_id'],
            'status' => 'completed'
        ]);
    }
} 