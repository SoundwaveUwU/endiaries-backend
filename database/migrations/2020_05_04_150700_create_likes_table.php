<?php

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignIdFor(Blog::class)
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(Post::class)
                ->constrained()
                ->onDelete('cascade');

            $table->unique(['blog_id', 'post_id']);

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
        Schema::dropIfExists('likes');
    }
}
