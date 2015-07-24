<!-- VAT / Extra Billing Information -->
<div class="panel panel-default">
	<div class="panel-heading">
		VAT / Extra Billing Information
	</div>

	<div class="panel-body">
		<div class="alert alert-info">
			If you need to add specific contact or tax information to your receipts, like your full business name,
			VAT identification number, or address of record, add it here. We'll make sure it shows up on every receipt.
		</div>

		<spark-errors form="@{{ extraBillingInfoForm }}"></spark-errors>

		<div class="alert alert-success" v-if="extraBillingInfoForm.updated">
			<strong>Done!</strong> Your extra billing information has been updated.
		</div>

		<form class="form-horizontal spark-form" role="form">
			<div class="form-group">
				<label for="key" class="col-md-3 control-label">Text</label>
				<div class="col-md-6">
					<textarea class="form-control" rows="7" v-model="extraBillingInfoForm.text"></textarea>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-3 col-md-6">
					<button type="submit" class="btn btn-primary" v-on="click: updateExtraBillingInfo" v-attr="disabled: extraBillingInfoForm.updating">
						<span v-if="extraBillingInfoForm.updating">
							<i class="fa fa-btn fa-spin fa-spinner "></i> Updating
						</span>

						<span v-if=" ! extraBillingInfoForm.updating">
							<i class="fa fa-btn fa-save"></i> Update
						</span>
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Invoice Listing -->
<div class="panel panel-default">
	<div class="panel-heading">
		Invoice History
	</div>

	<div class="panel-body">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Date</th>
					<th>Amount</th>
					<th class="text-right">Receipt</th>
				</tr>
			</thead>

			<tbody class="no-border-y">
				@foreach ($invoices as $invoice)
					<tr>
						<td>
							<strong>{{ $invoice->date()->format('Y-m-d') }}</strong>
						</td>
						<td>
							{{ $invoice->dollars() }}
						</td>
						<td class="text-right">
							<a href="{{ url('settings/user/plan/invoice/'.$invoice->id) }}">
								Download
							</a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
