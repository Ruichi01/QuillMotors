<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InquiryController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'vehicle' => ['required', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:3000'],
        ]);

        $validated['submitted_at'] = now()->toIso8601String();

        Storage::disk('local')->append(
            'inquiries.jsonl',
            json_encode($validated, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        return redirect()->route('home', ['#' => 'contact'])
            ->with('inquiry_success', 'Thanks! Your inquiry was received. We will be in touch soon.');
    }
}
