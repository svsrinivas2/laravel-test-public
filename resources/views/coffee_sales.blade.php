<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New ☕️ Sales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('coffee.sales.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="profit_margin" id="profitMargin" value="{{ $product_details['profit_margin']}}" />
                            <input type="hidden" name="shipping_cost" id="shippingCost" value="{{ $product_details['shipping_cost']}}" />
                            <input type="hidden" name="selling_price" id="sellingPrice" value="0" />
                            <div class="col-3">
                                <label for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity" required>
                            </div>
                            <div class="col-3">
                                <label for="unit_cost">Unit Cost (£)</label>
                                <input type="number" step="0.01" id="unit_cost" name="unit_cost" required>
                            </div>
                            <div class="col-3">
                                <label for="profit_margin">Selling Price</label>
                                <div> £ <span id='selling_price'>00.00</span></div>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary">Record Sale</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight table-heading">Previous Sales</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Selling Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($sales) ===0)
                            <tr>
                                <td colspan="3" class="text-center">No Sales Found</td>
                            </tr>
                            @else
                            @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $sale->quantity }}</td>
                                <td>£{{ $sale->unit_cost }}</td>
                                <td>£{{ $sale->selling_price }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    $(document).ready(function() {
        // Function to perform the calculation and update the result
        function updateResult() {
            var quantity = parseInt($('#quantity').val());
            var unit_cost = parseFloat($('#unit_cost').val());

            // Validate the inputs to ensure they are numbers
            if (!isNaN(quantity) && !isNaN(unit_cost)) {
                var cost = quantity * unit_cost; // Calclate actual cost
                var profit_margin = parseInt($('#profitMargin').val()) / 100;
                var shipping_cost = parseInt($('#shippingCost').val());
                var selling_price = (cost / (1 - profit_margin)) + shipping_cost;
                $('#sellingPrice').text(selling_price.toFixed(2));
                $('#selling_price').text(selling_price.toFixed(2)); // Display the result with 2 decimal places 
            }
        }

        // Attach blur event handlers to the input fields
        $('#quantity, #unit_cost').blur(function() {
            updateResult();
        });

        // Alternatively, attach keyup event handlers for real-time updates
        $('#quantity, #unit_cost').keyup(function() {
            updateResult();
        });
    });
</script>