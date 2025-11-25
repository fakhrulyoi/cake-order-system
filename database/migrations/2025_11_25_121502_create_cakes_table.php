public function up()
{
    Schema::create('cakes', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 8, 2);
        $table->string('size')->nullable();
        $table->string('image')->nullable();
        $table->enum('status', ['available', 'unavailable'])->default('available');
        $table->string('category')->nullable();
        $table->timestamps();
    });
}
