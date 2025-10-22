<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;


class ClientController extends Controller {
    
    public function index(Request $request) {
        $query = Client::query()->withCount('purchases');

        // Filtros
        if ($s = $request->get('search')) {
            $query->where(function($q) use ($s) {
                $q->where('name','like','%'.$s.'%')
                ->orWhere('email','like','%'.$s.'%')
                ->orWhere('phone','like','%'.$s.'%');
            });
        }

        if ($min = $request->get('min_spent')) {
            $query->whereHas('purchases', function($q) use ($min){
                $q->select('client_id')
                ->groupBy('client_id')
                ->havingRaw('SUM(amount) >= ?', [(float)$min]);
            });
        }

        if ($max = $request->get('max_spent')) {
            $query->whereHas('purchases', function($q) use ($max){
                $q->select('client_id')
                ->groupBy('client_id')
                ->havingRaw('SUM(amount) <= ?', [(float)$max]);
            });
        }

        $clients = $query->latest()->paginate(10)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    public function create() {
        return view('clients.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:clients,email'],
            'phone' => ['nullable','string','max:30'],
            'profile_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $request->file('profile_photo')->store('clients','public');
        }

        $client = Client::create($data);

        return redirect()->route('clients.show', $client)->with('ok','Cliente criado!');
    }

    public function show(Client $client) {
        $client->load('purchases');
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client) {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client) {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:clients,email,'.$client->id],
            'phone' => ['nullable','string','max:30'],
            'profile_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($client->profile_photo_path) {
                Storage::disk('public')->delete($client->profile_photo_path);
            }
            $data['profile_photo_path'] = $request->file('profile_photo')->store('clients','public');
        }

        $client->update($data);
        return redirect()->route('clients.show', $client)->with('ok','Cliente atualizado!');
    }

    public function destroy(Client $client) {
        if ($client->profile_photo_path) {
            Storage::disk('public')->delete($client->profile_photo_path);
        }
        $client->delete();
        return redirect()->route('clients.index')->with('ok','Cliente removido.');
    }

    // --- Compras ---
    public function addPurchase(Request $request, Client $client) {
        $data = $request->validate([
            'purchased_at' => ['required','date'],
            'amount' => ['required','numeric','min:0'],
            'description' => ['nullable','string','max:255'],
        ]);
 
        $client->purchases()->create($data);
        return back()->with('ok','Compra adicionada!');
    }

    // --- Exportações ---
    public function exportCsv(Request $request): StreamedResponse {
        $fileName = 'clients_'.now()->format('Ymd_His').'.csv';
        $clients = $this->queryForExport($request)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        return response()->streamDownload(function() use ($clients) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID','Nome','Email','Telefone','Total Gasto','Criado em']);
            foreach ($clients as $c) {
                fputcsv($out, [
                    $c->id,
                    $c->name,
                    $c->email,
                    $c->phone,
                    number_format($c->purchases_sum_amount ?? 0, 2, ',', '.'),
                    $c->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($out);
        }, $fileName, $headers);
    }

    public function exportPdf(Request $request) {
        $clients = $this->queryForExport($request)->get();
        $pdf = Pdf::loadView('clients.export-pdf', compact('clients'));
        return $pdf->download('clients_'.now()->format('Ymd_His').'.pdf');
    }

    private function queryForExport(Request $request) {
        $q = Client::query()
            ->select('clients.*')
            ->withCount('purchases')
            ->leftJoin('purchases', 'purchases.client_id', '=', 'clients.id')
            ->groupBy('clients.id')
            ->selectRaw('COALESCE(SUM(purchases.amount),0) as purchases_sum_amount');

        if ($s = $request->get('search')) {
            $q->where(function($w) use ($s){
                $w->where('clients.name','like','%'.$s.'%')
                ->orWhere('clients.email','like','%'.$s.'%')
                ->orWhere('clients.phone','like','%'.$s.'%');
            });
        }

        if ($min = $request->get('min_spent')) {
            $q->having('purchases_sum_amount','>=',(float)$min);
        }
        
        if ($max = $request->get('max_spent')) {
            $q->having('purchases_sum_amount','<=',(float)$max);
        }

        return $q->orderBy('clients.created_at','desc');
    }
}