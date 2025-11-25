public function up()
{
    Schema::create('store_settings', function (Blueprint $table) {
        $table->id();
        $table->string('store_name');
        $table->string('whatsapp_number');
        $table->string('theme_color')->default('#14532D');
        $table->string('banner_image')->nullable();
        $table->text('description')->nullable();
        $table->enum('store_status', ['open', 'closed'])->default('open');
        $table->timestamps();
    });
}
