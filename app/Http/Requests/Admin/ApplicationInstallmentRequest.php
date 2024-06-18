<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationInstallmentRequest extends BaseAdminRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'installments' => 'required|array',
            'installments.*.title' => 'required|string',
            'installments.*.amount' => 'required|numeric',
            'installments.*.payment_date' => 'required|date',
        ];
    }
}
