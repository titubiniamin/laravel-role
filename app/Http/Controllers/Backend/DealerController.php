<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
//use App\Http\Requests\DealerRequest;
use App\Models\Dealer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Request;

class DealerController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['dealer.view']);

        return view('backend.pages.dealers.index', [
            'dealers' => Dealer::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['dealer.create']);

        return view('backend.pages.dealers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['dealer.create']);

        Dealer::create($request->validated());

        session()->flash('success', 'Dealer has been created.');
        return redirect()->route('admin.dealers.index');
    }

    public function edit(int $id): Renderable|RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['dealer.update']);

        $dealer = Dealer::findOrFail($id);

        return view('backend.pages.dealers.edit', compact('dealer'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['dealer.update']);

        $dealer = Dealer::findOrFail($id);
        $dealer->update($request->validated());

        session()->flash('success', 'Dealer has been updated.');
        return redirect()->route('admin.dealers.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['dealer.delete']);

        $dealer = Dealer::findOrFail($id);
        $dealer->delete();

        session()->flash('success', 'Dealer has been deleted.');
        return redirect()->route('admin.dealers.index');
    }
}

