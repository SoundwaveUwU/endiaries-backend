<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignIdFor(Blog::class, 'from_blog_id')
                ->constrained('blogs')
                ->onDelete('cascade');

            $table->foreignIdFor(Blog::class, 'to_blog_id')
                ->constrained('blogs')
                ->onDelete('cascade');

            $table->unique(['from_blog_id', 'to_blog_id']);

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follows');
    }
}
