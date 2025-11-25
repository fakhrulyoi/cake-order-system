public function up()
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('cake_id')->constrained()->onDelete('cascade');
        $table->integer('quantity');
        $table->decimal('price', 8, 2);
        $table->text('special_notes')->nullable();
        $table->timestamps();
    });
}
