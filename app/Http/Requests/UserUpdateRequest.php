<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'firstName' => ['sometimes', 'required', 'regex:/^[A-Za-z\s]+$/'],
      'surname' => ['sometimes', 'required', 'regex:/^[A-Za-z\s]+$/'],
    ];
  }
  protected function prepareForValidation(): void
  {
    $this->merge([
      'first_name' => $this->firstName,
    ]);
  }
}
