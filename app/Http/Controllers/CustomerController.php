<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $customers = User::orderBy('created_at', 'desc')->get();
        return $this->successResponse($customers, 'Customers retrieved.');
    }

    public function show($id)
    {
        try {
            $customer = User::with('orders')->findOrFail($id);
            return $this->successResponse($customer, 'Customer retrieved.');
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Customer not found.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'mobile'   => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $customer = User::create($validated);

        return $this->successResponse($customer, 'Customer created successfully.', 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $customer = User::findOrFail($id);

            $validated = $request->validate([
                'name'     => 'sometimes|required|string|max:255',
                'email'    => 'sometimes|required|email|unique:users,email,' . $id,
                'mobile'   => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }

            $customer->update($validated);

            return $this->successResponse($customer, 'Customer updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Customer not found.');
        }
    }

    public function destroy($id)
    {
        try {
            $customer = User::findOrFail($id);
            $customer->delete();

            return $this->successResponse(null, 'Customer deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Customer not found.');
        }
    }
}
