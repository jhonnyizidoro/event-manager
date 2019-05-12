<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipRules extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::table('addresses', function (Blueprint $table) {
			$table->unsignedBigInteger('city_id')->nullable();
			$table->foreign('city_id')->references('id')->on('cities');
		});
		
		Schema::table('certificates', function (Blueprint $table) {
			$table->unsignedBigInteger('event_id');
			$table->foreign('event_id')->references('id')->on('events');
		});
		
		Schema::table('cities', function (Blueprint $table) {
			$table->unsignedBigInteger('state_id');
			$table->foreign('state_id')->references('id')->on('states');
		});
		
		Schema::table('comments', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
		});
		
		Schema::table('event_administrators', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->unsignedBigInteger('event_id');
			$table->foreign('event_id')->references('id')->on('events');
		});

		Schema::table('event_series', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::table('event_staff', function (Blueprint $table) {
			$table->unsignedBigInteger('event_id');
			$table->foreign('event_id')->references('id')->on('events');
			$table->unsignedBigInteger('staff_id');
			$table->foreign('staff_id')->references('id')->on('staff');
		});
		
		Schema::table('events', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->unsignedBigInteger('event_serie_id')->nullable();
			$table->foreign('event_serie_id')->references('id')->on('event_series');
			$table->unsignedBigInteger('address_id');
			$table->foreign('address_id')->references('id')->on('addresses');
			$table->unsignedBigInteger('category_id');
			$table->foreign('category_id')->references('id')->on('categories');
		});
		
		Schema::table('follows', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
		});
		
		Schema::table('notifications', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
		});
		
		Schema::table('staff', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
		});
		
		Schema::table('posts', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
		});
		
		Schema::table('shares', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
		});
		
		Schema::table('subscriptions', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->unsignedBigInteger('event_id');
			$table->foreign('event_id')->references('id')->on('events');
		});

		Schema::table('user_certificates', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->unsignedBigInteger('certificate_id');
			$table->foreign('certificate_id')->references('id')->on('certificates');
		});
		
		Schema::table('user_interests', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->unsignedBigInteger('category_id');
			$table->foreign('category_id')->references('id')->on('categories');
		});
		
		Schema::table('user_preferences', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
		});
		
		Schema::table('user_profiles', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
		});
		
		Schema::table('user_reactions', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->unsignedBigInteger('reaction_id');
			$table->foreign('reaction_id')->references('id')->on('reactions');
		});

		Schema::table('user_staff', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->unsignedBigInteger('staff_id');
			$table->foreign('staff_id')->references('id')->on('staff');
		});
		
		Schema::table('users', function (Blueprint $table) {
			$table->unsignedBigInteger('address_id')->nullable();
			$table->foreign('address_id')->references('id')->on('addresses');
		});
	}
	
	public function down()
	{
		Schema::table('addresses', function (Blueprint $table) {
			$table->dropForeign(['city_id']);
			$table->dropColumn('city_id');
		});
		
		Schema::table('certificates', function (Blueprint $table) {
			$table->dropForeign(['event_id']);
			$table->dropColumn('event_id');
		});
		
		Schema::table('cities', function (Blueprint $table) {
			$table->dropForeign(['state_id']);
			$table->dropColumn('state_id');
		});
		
		Schema::table('comments', function (Blueprint $table) {
			$table->dropForeign(['user_id']);
			$table->dropColumn('user_id');
		});
		
		Schema::table('event_administrators', function (Blueprint $table) {
			$table->dropForeign(['event_id']);
			$table->dropForeign(['user_id']);
			$table->dropColumn('event_id');
			$table->dropColumn('user_id');
		});

		Schema::table('event_series', function (Blueprint $table) {
			$table->dropForeign(['user_id']);
			$table->dropColumn('user_id');
		});

		Schema::table('event_staff', function (Blueprint $table) {
			$table->dropForeign(['staff_id']);
			$table->dropForeign(['event_id']);
			$table->dropColumn('staff_id');
			$table->dropColumn('event_id');
		});
		
		Schema::table('events', function (Blueprint $table) {
			$table->dropForeign(['user_id']);
			$table->dropForeign(['event_serie_id']);
			$table->dropForeign(['address_id']);
			$table->dropForeign(['category_id']);
			$table->dropColumn('user_id');
			$table->dropColumn('event_serie_id');
			$table->dropColumn('address_id');
			$table->dropColumn('category_id');
		});
		
		Schema::table('follows', function (Blueprint $table) {
			$table->dropForeign(['user_id']);
			$table->dropColumn('user_id');
		});
		
		Schema::table('notifications', function (Blueprint $table) {
			$table->dropForeign(['user_id']);
			$table->dropColumn('user_id');
		});
		
		Schema::table('staff', function (Blueprint $table) {
			$table->dropForeign(['user_id']);
			$table->dropColumn('user_id');
		});
		
		Schema::table('posts', function (Blueprint $table) {
			$table->dropForeign(['user_id']);
			$table->dropColumn('user_id');
		});
		
		Schema::table('shares', function (Blueprint $table) {
			$table->dropForeign(['user_id']);
			$table->dropColumn('user_id');
		});
		
		Schema::table('subscriptions', function (Blueprint $table) {
			$table->dropForeign(['event_id']);
			$table->dropForeign(['user_id']);
			$table->dropColumn('event_id');
			$table->dropColumn('user_id');
		});

		Schema::table('user_certificates', function (Blueprint $table) {
			$table->dropForeign(['certificate_id']);
			$table->dropForeign(['user_id']);
			$table->dropColumn('certificate_id');
			$table->dropColumn('user_id');
		});
		
		Schema::table('user_interests', function (Blueprint $table) {
			$table->dropForeign(['category_id']);
			$table->dropForeign(['user_id']);
			$table->dropColumn('category_id');
			$table->dropColumn('user_id');
		});
		
		Schema::table('user_preferences', function (Blueprint $table) {
			$table->dropForeign(['user_id']);
			$table->dropColumn('user_id');
		});
		
		Schema::table('user_profiles', function (Blueprint $table) {
			$table->dropForeign(['user_id']);
			$table->dropColumn('user_id');
		});
		
		Schema::table('user_reactions', function (Blueprint $table) {
			$table->dropForeign(['reaction_id']);
			$table->dropForeign(['user_id']);
			$table->dropColumn('reaction_id');
			$table->dropColumn('user_id');
		});

		Schema::table('user_staff', function (Blueprint $table) {
			$table->dropForeign(['staff_id']);
			$table->dropForeign(['user_id']);
			$table->dropColumn('staff_id');
			$table->dropColumn('user_id');
		});
		
		Schema::table('users', function (Blueprint $table) {
			$table->dropForeign(['address_id']);
			$table->dropColumn('address_id');
		});
	}
}
