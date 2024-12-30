<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use App\Services\TravelOrderService;
use Illuminate\Http\Request;
use App\Http\Requests\TravelOrderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TravelOrderController extends Controller
{
    protected $travelOrderService;

    public function __construct(TravelOrderService $travelOrderService)
    {
        $this->travelOrderService = $travelOrderService;
    }

    public function index(Request $request)
    {
        $query = TravelOrder::where('user_id', Auth::id());

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->where('start_date', '>=', $request->start_date);
            $query->where('end_date', '<=', $request->end_date);
        }

        if ($request->has('destination')) {
            $query->where('destination', 'like', "%{$request->destination}%");
        }

        $travelOrders = $query->get();

        return response()->json($travelOrders);
    }

    public function show($id)
    {
        $travelOrder = $this->findTravelOrderById($id);

        if (!$travelOrder) {
            return response()->json(['message' => 'Pedido de viagem não encontrado ou não autorizado.'], 404);
        }

        return response()->json($travelOrder);
    }

    public function store(TravelOrderRequest $request)
    {
        $validated = $request->validated();

        $travelOrder = TravelOrder::create([
            'user_id' => Auth::id(),
            'requester_name' => Auth::user()->name,
            'destination' => $validated['destination'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'solicitado',
        ]);

        return response()->json($travelOrder, 201);
    }

    public function update(TravelOrderRequest $request, $id)
    {
        $travelOrder = $this->findTravelOrderById($id);

        if (!$travelOrder) {
            return response()->json(['message' => 'Pedido de viagem não encontrado ou não autorizado.'], 404);
        }

        $validated = $request->validated();

        try {
            if ($validated['status'] === 'cancelado') {
                $this->travelOrderService->canCancel($travelOrder);
            }

            $travelOrder->status = $validated['status'];
            $travelOrder->save();

            if (in_array($validated['status'], ['aprovado', 'cancelado'])) {
                $this->travelOrderService->notifyUser($travelOrder, $validated['status']);
            }

            return response()->json($travelOrder);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->errors()['status'][0] ?? 'Erro desconhecido'
            ], 400);
        }
    }

    protected function findTravelOrderById($id)
    {
        return TravelOrder::where('user_id', Auth::id())->find($id);
    }
}
