<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\Http\Request;

class PluginController extends Controller
{
    public function index()
    {
        $plugins = Plugin::all();
        return view('plugins.index', compact('plugins'));
    }

    public function status($id)
    {
        $plugin = Plugin::find($id);
        $plugin->status = $plugin->status == 'active' ? 'inactive' : 'active';
        $plugin->save();
        return redirect()->route('plugins')->with('success', __('Plugin status updated successfully'));
    }
}
