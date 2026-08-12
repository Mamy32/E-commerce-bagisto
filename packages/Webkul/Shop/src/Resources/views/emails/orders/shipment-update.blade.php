@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px;font-weight: 600;color: #2969FF">
            Shipment Update
        </span> <br>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('shop::app.emails.dear', ['customer_name' => $shipment->order->customer_full_name]),👋
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            Your shipment for order <a href="{{ route('shop.customers.account.orders.view', $shipment->order->id) }}" style="color: #2969FF;">#{{ $shipment->order->increment_id }}</a> has been updated.
            <br/><br/>
            <strong>Tracking Number:</strong> {{ $shipment->track_number ?? 'N/A' }}
        </p>
    </div>

    <div style="font-size: 20px;font-weight: 600;color: #121A26">
        @lang('shop::app.emails.orders.created.summary')
    </div>

    <div style="display: flex;flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 40px;">
        @if ($shipment->order->shipping_address)
            <div style="line-height: 25px;">
                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('shop::app.emails.orders.shipping-address')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;margin-bottom: 40px;">
                    {{ $shipment->order->shipping_address->company_name ?? '' }}<br/>

                    {{ $shipment->order->shipping_address->name }}<br/>

                    {{ $shipment->order->shipping_address->address }}<br/>

                    {{ $shipment->order->shipping_address->postcode . " " . $shipment->order->shipping_address->city }}<br/>

                    {{ $shipment->order->shipping_address->state }}<br/>

                    ---<br/>

                    @lang('shop::app.emails.orders.contact') : {{ $shipment->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('shop::app.emails.orders.shipping')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;">
                    {{ $shipment->order->shipping_title }}
                </div>
            </div>
        @endif

        @if ($shipment->order->billing_address)
            <div style="line-height: 25px;">
                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('shop::app.emails.orders.billing-address')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;margin-bottom: 40px;">
                    {{ $shipment->order->billing_address->company_name ?? '' }}<br/>

                    {{ $shipment->order->billing_address->name }}<br/>

                    {{ $shipment->order->billing_address->address }}<br/>

                    {{ $shipment->order->billing_address->postcode . " " . $shipment->order->billing_address->city }}<br/>

                    {{ $shipment->order->billing_address->state }}<br/>

                    ---<br/>

                    @lang('shop::app.emails.orders.contact') : {{ $shipment->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('shop::app.emails.orders.payment')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;">
                    {{ $shipment->order->payment->method_title ?: core()->getConfigData('sales.payment_methods.' . $shipment->order->payment->method . '.title') }}
                </div>

                @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($shipment->order->payment->method); @endphp

                @if (! empty($additionalDetails))
                    <div style="font-size: 16px; color: #384860;">
                        <div>{{ $additionalDetails['title'] }}</div>

                        <div>{{ $additionalDetails['value'] }}</div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div style="padding-bottom: 40px;border-bottom: 1px solid #CBD5E1;">
        <table style="overflow-x: auto; border-collapse: collapse;
        border-spacing: 0;width: 100%">
            <thead>
                <tr style="color: #121A26;border-top: 1px solid #CBD5E1;border-bottom: 1px solid #CBD5E1;">
                    @foreach (['sku', 'name', 'price', 'qty'] as $item)
                        <th style="text-align: left;padding: 15px">
                            @lang('shop::app.emails.orders.' . $item)
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody style="font-size: 16px;font-weight: 400;color: #384860;">
                @foreach ($shipment->order->items as $item)
                    <tr style="vertical-align: text-top;">
                        <td style="text-align: left;padding: 15px">
                            {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                        </td>

                        <td style="text-align: left;padding: 15px">
                            {{ $item->name }}

                            @if (isset($item->additional['attributes']))
                                <div>
                                    @foreach ($item->additional['attributes'] as $attribute)
                                        @if (
                                            ! isset($attribute['attribute_type'])
                                            || $attribute['attribute_type'] !== 'file'
                                        )
                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}<br>
                                        @else
                                            {{ $attribute['attribute_name'] }} :

                                            <a
                                                href="{{ Storage::url($attribute['option_label']) }}"
                                                class="text-blue-600 hover:underline"
                                                download="{{ File::basename($attribute['option_label']) }}"
                                            >
                                                {{ File::basename($attribute['option_label']) }}
                                            </a>

                                            <br>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td style="display: flex;flex-direction: column;text-align: left;padding: 15px">
                            @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                {{ core()->formatPrice($item->price_incl_tax, $shipment->order->order_currency_code) }}
                            @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                {{ core()->formatPrice($item->price_incl_tax, $shipment->order->order_currency_code) }}

                                <span style="font-size: 12px; white-space: nowrap">
                                    @lang('shop::app.emails.orders.excl-tax')

                                    <span style="font-weight: 600">
                                        {{ core()->formatPrice($item->price, $shipment->order->order_currency_code) }}
                                    </span>
                                </span>
                            @else
                                {{ core()->formatPrice($item->price, $shipment->order->order_currency_code) }}
                            @endif
                        </td>

                        <td style="text-align: left;padding: 15px">
                            {{ $item->qty_ordered }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="display: grid;justify-content: end;font-size: 16px;color: #384860;line-height: 30px;padding-top: 20px;padding-bottom: 20px;">
        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax')
            <div style="display: grid;gap: 20px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>
                    @lang('shop::app.emails.orders.subtotal')
                </span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($shipment->order->sub_total, $shipment->order->order_currency_code_incl_tax) }}
                </span>
            </div>
        @elseif (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
            <div style="display: grid;gap: 20px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>
                    @lang('shop::app.emails.orders.subtotal-excl-tax')
                </span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($shipment->order->sub_total, $shipment->order->order_currency_code) }}
                </span>
            </div>

            <div style="display: grid;gap: 20px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>
                    @lang('shop::app.emails.orders.subtotal-incl-tax')
                </span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($shipment->order->sub_total, $shipment->order->order_currency_code_incl_tax) }}
                </span>
            </div>
        @else
            <div style="display: grid;gap: 20px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>
                    @lang('shop::app.emails.orders.subtotal')
                </span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($shipment->order->sub_total, $shipment->order->order_currency_code) }}
                </span>
            </div>
        @endif

        @if ($shipment->order->shipping_address)
            @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax')
                <div style="display: grid;gap: 20px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                    <span>
                        @lang('shop::app.emails.orders.shipping-handling')
                    </span>

                    <span style="text-align: right;">
                        {{ core()->formatPrice($shipment->order->shipping_amount_incl_tax, $shipment->order->order_currency_code) }}
                    </span>
                </div>
            @elseif (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                <div style="display: grid;gap: 20px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                    <span>
                        @lang('shop::app.emails.orders.shipping-handling-excl-tax')
                    </span>

                    <span style="text-align: right;">
                        {{ core()->formatPrice($shipment->order->shipping_amount, $shipment->order->order_currency_code) }}
                    </span>
                </div>

                <div style="display: grid;gap: 20px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                    <span>
                        @lang('shop::app.emails.orders.shipping-handling-incl-tax')
                    </span>

                    <span style="text-align: right;">
                        {{ core()->formatPrice($shipment->order->shipping_amount_incl_tax, $shipment->order->order_currency_code) }}
                    </span>
                </div>
            @else
                <div style="display: grid;gap: 20px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                    <span>
                        @lang('shop::app.emails.orders.shipping-handling')
                    </span>

                    <span style="text-align: right;">
                        {{ core()->formatPrice($shipment->order->shipping_amount, $shipment->order->order_currency_code) }}
                    </span>
                </div>
            @endif
        @endif

        <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
            <span>
                @lang('shop::app.emails.orders.tax')
            </span>

            <span style="text-align: right;">
                {{ core()->formatPrice($shipment->order->tax_amount, $shipment->order->order_currency_code) }}
            </span>
        </div>

        @if ($shipment->order->discount_amount > 0)
            <div style="display: grid;gap: 20px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>
                    @lang('shop::app.emails.orders.discount')
                </span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($shipment->order->discount_amount, $shipment->order->order_currency_code) }}
                </span>
            </div>
        @endif

        <div style="display: grid;gap: 20px;grid-template-columns: repeat(2, minmax(0, 1fr));font-weight: bold">
            <span>
                @lang('shop::app.emails.orders.grand-total')
            </span>

            <span style="text-align: right;">
                {{ core()->formatPrice($shipment->order->grand_total, $shipment->order->order_currency_code) }}
            </span>
        </div>
    </div>
@endcomponent
