<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('profiles', function(Blueprint $table) {
			$table->foreign('language_id')->references('id')->on('languages')
						->onDelete('restrict')
						->onUpdate('no action');
		});
		Schema::table('survey_matchrules', function(Blueprint $table) {
			$table->foreign('matchgroup_id')->references('id')->on('survey_matchgroups')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('survey_repeats', function(Blueprint $table) {
			$table->foreign('survey_id')->references('id')->on('surveys')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('survey_entities', function(Blueprint $table) {
			$table->foreign('survey_id')->references('id')->on('surveys')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('survey_entity_l_skip', function(Blueprint $table) {
			$table->foreign('option_id')->references('id')->on('survey_entity_options')
						->onDelete('restrict')
						->onUpdate('no action');
		});
		Schema::table('survey_entity_l_skip', function(Blueprint $table) {
			$table->foreign('entity_id')->references('id')->on('survey_entities')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('survey_answers', function(Blueprint $table) {
			$table->foreign('profile_id')->references('id')->on('profiles')
						->onDelete('restrict')
						->onUpdate('no action');
		});
		Schema::table('survey_answers', function(Blueprint $table) {
			$table->foreign('survey_id')->references('id')->on('surveys')
						->onDelete('restrict')
						->onUpdate('no action');
		});
		Schema::table('survey_answers', function(Blueprint $table) {
			$table->foreign('entity_id')->references('id')->on('survey_entities')
						->onDelete('restrict')
						->onUpdate('no action');
		});
		Schema::table('survey_matchgroups', function(Blueprint $table) {
			$table->foreign('survey_id')->references('id')->on('surveys')
						->onDelete('cascade')
						->onUpdate('no action');
		});
	}

	public function down()
	{
		Schema::table('profiles', function(Blueprint $table) {
			$table->dropForeign('profiles_language_id_foreign');
		});
		Schema::table('survey_matchrules', function(Blueprint $table) {
			$table->dropForeign('survey_matchrules_matchgroup_id_foreign');
		});
		Schema::table('survey_repeats', function(Blueprint $table) {
			$table->dropForeign('survey_repeats_survey_id_foreign');
		});
		Schema::table('survey_entities', function(Blueprint $table) {
			$table->dropForeign('survey_entities_survey_id_foreign');
		});
		Schema::table('survey_entity_l_skip', function(Blueprint $table) {
			$table->dropForeign('survey_entity_l_skip_option_id_foreign');
		});
		Schema::table('survey_entity_l_skip', function(Blueprint $table) {
			$table->dropForeign('survey_entity_l_skip_entity_id_foreign');
		});
		Schema::table('survey_answers', function(Blueprint $table) {
			$table->dropForeign('survey_answers_profile_id_foreign');
		});
		Schema::table('survey_answers', function(Blueprint $table) {
			$table->dropForeign('survey_answers_survey_id_foreign');
		});
		Schema::table('survey_answers', function(Blueprint $table) {
			$table->dropForeign('survey_answers_entity_id_foreign');
		});
		Schema::table('survey_matchgroups', function(Blueprint $table) {
			$table->dropForeign('survey_matchgroups_survey_id_foreign');
		});
	}
}