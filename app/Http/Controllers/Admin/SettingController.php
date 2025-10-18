<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load all settings
        $settings = [
            'site_name' => Setting::get('site_name', 'Shopy'),
            'site_description' => Setting::get('site_description', 'Laravel E-Commerce System'),
            'contact_email' => Setting::get('contact_email', 'admin@example.com'),
            'contact_phone' => Setting::get('contact_phone', '+1234567890'),
            'address' => Setting::get('address', '123 Admin Street'),
            'currency' => Setting::get('currency', 'USD'),
            'timezone' => Setting::get('timezone', 'UTC'),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }
    
    /**
     * Update the settings in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
        ]);
        
        // Update all settings
        Setting::set('site_name', $request->site_name);
        Setting::set('site_description', $request->site_description);
        Setting::set('contact_email', $request->contact_email);
        Setting::set('contact_phone', $request->contact_phone);
        Setting::set('address', $request->address);
        Setting::set('currency', $request->currency);
        Setting::set('timezone', $request->timezone);
        
        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
