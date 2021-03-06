<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Http\Request;
use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Models\Module;

class RunController extends Controller
{
    public function index()
    {
        $runs = Run::query()
            ->with('groups', 'groups.stats')
            ->latest()
            ->get();

        if ($runs->isEmpty()) {
            return redirect(route('enlighten.intro'));
        }

        return view('enlighten::dashboard.index', [
            'runs' => $runs
        ]);
    }

    public function show(Request $request, ?Run $run = null)
    {
        $tabs = $this->getTabs();

        if ($request->route('suite') === null) {
            $suite = $tabs->first();
        } else {
            $suite = $tabs->firstWhere('slug', $request->route('suite'));
        }

        if ($suite === null) {
            return redirect(route('enlighten.run.index'));
        }

        $groups = $run->groups()->with('stats')->bySuite($suite)->get();

        $modules = Module::all();

        $modules->addGroups($groups);

        return view('enlighten::suite.show', [
            'modules' => $modules->whereHasGroups(),
            'title' => 'Dashboard',
            'suite' => $suite
        ]);
    }
}
