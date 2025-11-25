public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_number')->unique();
        $table->string('customer_name');
        $table->string('customer_phone');
        $table->string('customer_whatsapp')->nullable();
        $table->dateTime('pickup_datetime');
        $table->text('notes')->nullable();
        $table->decimal('total_amount', 10, 2);
        $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
        $table->enum('order_status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
        $table->timestamps();
    });
}
