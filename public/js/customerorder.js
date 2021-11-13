$(document).ready(function(){
	
	// autocomplete on product code
	autocomplete('order_id', 'customer_orders', 'order_id');
	autocomplete('order_item_id', 'customer_orders', 'order_item_id');
	autocomplete('buyer_name', 'customer_orders', 'buyer_name');
	autocomplete('buyer_phone_number', 'customer_orders', 'buyer_phone_number');
	autocomplete('product_name', 'customer_orders', 'product_name');
	autocomplete('sku', 'customer_orders', 'sku');
});