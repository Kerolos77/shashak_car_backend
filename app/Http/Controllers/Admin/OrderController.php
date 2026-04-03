<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\Setting;
use App\Models\Income;
use App\Models\WalletTransaction;
use App\Events\TripStatusUpdated;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.order.index');
    }

    public function manual_index()
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Orders that are currently "stuck" or waiting
        $orders = Order::whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_SEARCHING,
            Order::STATUS_NEGOTIATING,
            Order::STATUS_USER_ACCEPT_OFFER,
            Order::STATUS_PAYMENT_PENDING,
            Order::STATUS_PAYMENT_FAILED,
            Order::STATUS_PAYMENT_REQUIRED
        ])->latest()->paginate(10);

        return view('admin.order.manual', compact('orders'));
    }

    public function manual_drivers(Order $order)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Find drivers compatible with this order's service and currently available
        $drivers = User::availableDrivers()
            ->whereHas('profile', function($q) use ($order) {
                $q->where('service_id', $order->service_id);
            })->get();

        return view('admin.order.manual_drivers', compact('order', 'drivers'));
    }

    public function manual_assign(Request $request, Order $order)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'driver_id' => 'required|exists:users,id'
        ]);

        $driver = User::find($request->driver_id);

        // 1. Bypass Payment State
        $order->update([
            'driver_id' => $driver->id,
            'status' => Order::STATUS_ASSIGNED,
            'payment_status' => Order::PAYMENT_PAID, // Admin bypass
            'assigned_at' => Carbon::now(),
            'is_accept' => Carbon::now(),
            // Ensure driver info is saved
            'driver_name' => $driver->full_name,
            'driver_phone' => $driver->phone_number ?? '',
        ]);

        // 2. Deduct Commission from Driver
        $setting = Setting::first();
        $commissionPercentage = $setting->commission_percentage ?? 0;
        if ($commissionPercentage > 0) {
            $commissionAmount = ($order->offer_rate * $commissionPercentage) / 100;
            
            $driver->update([
                'wallet_amount' => $driver->wallet_amount - $commissionAmount
            ]);

            Income::create([
                'order_id' => $order->id,
                'amount' => $commissionAmount
            ]);

            WalletTransaction::create([
                'user_id' => $driver->id,
                'amount' => $commissionAmount,
                'type' => 'withdraw',
                'description' => 'Commission for manual assignment (Order #' . $order->id . ')'
            ]);
        }

        // 3. Notify
        $driver->sendPushNotification("تم تعيينك يدوياً!", "تم تعيينك لرحلة جديدة بواسطة الإدارة.", ['order_id' => $order->id, 'type' => 'offer_accepted']);
        if ($order->user) {
            $order->user->sendPushNotification("تم تعيين سائق لرحلتك", "قام المسؤول بتعيين سائق لرحلتك يدوياً.", ['order_id' => $order->id, 'type' => 'trip_update']);
        }

        TripStatusUpdated::dispatch($order->fresh());

        return redirect()->route('admin.orders.manual_index')->with('success', 'Driver assigned successfully manually.');
    }

    public function create()
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.order.create');
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.order.edit', compact('order'));
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load('user');

        return view('admin.order.show', compact('order'));
    }
}
