<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Vui lòng nhập email, tên đăng nhập hoặc số điện thoại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        RateLimiter::hit($this->throttleKey());
        $user = User::where('email', $this->login)
            ->orWhere('name', $this->login)
            ->orWhere('phone', $this->login)
            ->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => 'Tài khoản không tồn tại.',
            ]);
        }

        if ($user->is_delete == 1 || $user->is_active !== 'active') {
            throw ValidationException::withMessages([
                'login' => 'Tài khoản của bạn đã bị khóa hoặc không hoạt động.',
            ]);
        }

        if (!Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => 'Mật khẩu không chính xác.',
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => 'Bạn đã đăng nhập sai quá nhiều lần. Vui lòng thử lại sau ' . $seconds . ' giây.',
        ]);
    }
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('login')) . '|' . $this->ip());
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'login' => trim($this->login),
        ]);
    }
}
