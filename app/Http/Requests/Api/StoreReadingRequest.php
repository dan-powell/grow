<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Models\Device;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReadingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // Rejected here so a device gets a 422 rather than the job failing on the queue
            'nickname' => ['required', 'string', Rule::exists(Device::class, 'nickname')],
            'timestamp' => ['required', 'date'],
            'readings' => ['required', 'array'],
            // A sensor with nothing to report may send null; that reading is skipped
            'readings.*' => ['nullable', 'numeric'],
        ];
    }
}
