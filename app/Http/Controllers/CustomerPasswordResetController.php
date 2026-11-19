<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerPasswordResetController extends Controller
{
    public function index()
    {
        return view('kasir.customers.reset-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ], [
            'phone.required' => 'Nomor HP wajib diisi.',
        ]);

        $customer = User::where('phone', $request->phone)
            ->where('role', 'pelanggan')
            ->first();

        if (! $customer) {
            return back()
                ->withInput()
                ->withErrors([
                    'phone' => 'Pelanggan dengan nomor HP tersebut tidak ditemukan.',
                ]);
        }

        $temporaryPassword = Str::random(10);

        $customer->password = $temporaryPassword;
        $customer->must_change_password = true;
        $customer->save();

        return back()->with('temporary_password', $temporaryPassword);
    }
}
