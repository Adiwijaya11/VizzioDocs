<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form (email input).
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send OTP to the given email.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Ambil email dari input user — INI yang akan menjadi tujuan pengiriman
        $emailTujuan = $request->email;

        // Cek apakah email ada di database
        $user = User::where('email', $emailTujuan)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar dalam sistem kami.'])->withInput();
        }

        // Generate OTP 6 digit acak
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Hapus OTP lama untuk email ini (baik yang sudah dipakai maupun belum)
        DB::table('password_reset_otps')->where('email', $emailTujuan)->delete();

        // Simpan OTP baru dengan waktu kedaluwarsa 5 menit
        DB::table('password_reset_otps')->insert([
            'email'      => $emailTujuan,
            'otp'        => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Kirim OTP ke email yang diinput user ($emailTujuan = $user->email)
        // Mail::to() memastikan email dikirim ke alamat yang benar, bukan ke FROM address
        try {
            Mail::to($emailTujuan)->send(new OtpMail($otp, $user->name));

            Log::info('OTP berhasil dikirim', [
                'to'    => $emailTujuan,
                'otp'   => $otp, // Hapus baris ini di production
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim OTP', [
                'to'    => $emailTujuan,
                'error' => $e->getMessage(),
            ]);

            // Hapus OTP yang sudah disimpan jika email gagal dikirim
            DB::table('password_reset_otps')->where('email', $emailTujuan)->delete();

            return back()
                ->withErrors(['email' => 'Gagal mengirim email OTP. Silakan coba lagi.'])
                ->withInput();
        }

        // Bersihkan session reset sebelumnya
        session()->forget(['password_reset_token', 'password_reset_email']);

        return redirect()->route('password.otp.form', ['email' => $emailTujuan])
            ->with('status', 'Kode OTP telah dikirim ke email Anda. Silakan periksa kotak masuk (berlaku 5 menit).');
    }

    /**
     * Show the OTP verification form.
     */
    public function showOtpForm(Request $request)
    {
        $email = $request->email;

        if (!$email) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.otp', compact('email'));
    }

    /**
     * Verify the OTP code.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        // Cari record OTP yang belum dipakai
        $otpRecord = DB::table('password_reset_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->whereNull('used_at')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->withInput();
        }

        // Cek apakah OTP sudah kedaluwarsa
        if (Carbon::now()->isAfter($otpRecord->expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang.'])->withInput();
        }

        // Tandai OTP sebagai sudah dipakai (sementara, akan dihapus permanen setelah reset password)
        DB::table('password_reset_otps')
            ->where('id', $otpRecord->id)
            ->update(['used_at' => now()]);

        // Buat token sementara untuk halaman reset password
        $resetToken = Str::random(64);

        // Simpan token di session — hanya berlaku untuk satu kali reset
        session([
            'password_reset_token' => $resetToken,
            'password_reset_email' => $request->email,
        ]);

        return redirect()->route('password.reset', [
            'token' => $resetToken,
            'email' => $request->email,
        ]);
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        // Verifikasi token sesuai dengan yang ada di session
        if (session('password_reset_token') !== $token || session('password_reset_email') !== $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Tautan atur ulang kata sandi tidak valid atau sudah kedaluwarsa. Silakan ulangi proses.']);
        }

        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Handle the password reset.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        // Verifikasi token sesuai session
        if (session('password_reset_token') !== $request->token || session('password_reset_email') !== $request->email) {
            return back()->withErrors(['email' => 'Sesi tidak valid. Silakan ulangi proses dari awal.']);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem kami.']);
        }

        // Update password user
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus OTP dari database agar tidak bisa digunakan lagi
        DB::table('password_reset_otps')->where('email', $request->email)->delete();

        // Bersihkan session
        session()->forget(['password_reset_token', 'password_reset_email']);

        Log::info('Password berhasil direset', ['email' => $request->email]);

        return redirect()->route('login')
            ->with('status', 'Kata sandi Anda berhasil diubah. Silakan masuk dengan kata sandi baru.');
    }

    /**
     * Resend OTP to the given email.
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $emailTujuan = $request->email;

        $user = User::where('email', $emailTujuan)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar dalam sistem kami.']);
        }

        // Generate OTP baru
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Hapus semua OTP lama untuk email ini
        DB::table('password_reset_otps')->where('email', $emailTujuan)->delete();

        // Simpan OTP baru
        DB::table('password_reset_otps')->insert([
            'email'      => $emailTujuan,
            'otp'        => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Kirim OTP ke email user yang diinput
        try {
            Mail::to($emailTujuan)->send(new OtpMail($otp, $user->name));

            Log::info('OTP resend berhasil', ['to' => $emailTujuan]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim ulang OTP', [
                'to'    => $emailTujuan,
                'error' => $e->getMessage(),
            ]);

            DB::table('password_reset_otps')->where('email', $emailTujuan)->delete();

            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Silakan coba lagi.']);
        }

        return redirect()->route('password.otp.form', ['email' => $emailTujuan])
            ->with('status', 'Kode OTP baru telah dikirim ke email Anda (berlaku 5 menit).');
    }
}
