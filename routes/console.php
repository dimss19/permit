<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('permit:test-email {role=staff} {site?}', function ($role, $site = null) {
    $role = strtolower($role);
    if (! in_array($role, ['staff', 'manager'])) {
        $this->error("Role harus 'staff' atau 'manager'.");
        return 1;
    }

    if ($site && ! in_array($site, \App\Models\Permit::SITES, true)) {
        $this->error('Site harus salah satu dari: ' . implode(', ', \App\Models\Permit::SITES) . '.');
        return 1;
    }

    $permitQuery = \App\Models\Permit::with('user')->latest();
    if ($site) {
        $permitQuery->where('site', $site);
    }
    $permit = $permitQuery->first();
    if (! $permit) {
        $this->error('Belum ada data permit untuk dijadikan contoh email.');
        return 1;
    }

    $userQuery = \App\Models\User::where('role', $role)
        ->where('is_active', true)
        ->whereNotNull('email')
        ->where('email', '!=', '');
    if ($role === 'staff' && ($site ?? $permit->site)) {
        $userQuery->where('site', $site ?? $permit->site);
    }
    $recipients = $userQuery->pluck('email')
        ->filter()->unique()->values()->all();

    if (empty($recipients)) {
        $this->error("Tidak ada akun {$role} yang aktif / punya email.");
        return 1;
    }

    // Sengaja tanpa try-catch supaya error SMTP langsung terlihat di terminal.
    \Illuminate\Support\Facades\Mail::to($recipients)
        ->send(new \App\Mail\PermitMasukMail($permit, $role));

    $this->info('Email tes terkirim ke: ' . implode(', ', $recipients));
    $this->info('Contoh permit: ' . $permit->no_permit);
})->purpose('Kirim email tes notifikasi permit masuk ke staff/manager HSE');
