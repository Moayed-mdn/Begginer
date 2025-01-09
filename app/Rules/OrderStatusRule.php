<?php

namespace App\Rules;

use App\Models\Order;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderStatusRule implements ValidationRule
{
    public $order;
    public function __construct(Order $order){
        $this->order=$order; 
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($value==='sent'&&$this->order->status==="sent")
            $fail('the order already sent');
        if($value==='receive'&&$this->order->status==="receive")
            $fail('the order already receive');
        if($value==='sent'&&$this->order->status==='receive')
            $fail('the order is received , you can make it in sent status !!!');

    }
}
