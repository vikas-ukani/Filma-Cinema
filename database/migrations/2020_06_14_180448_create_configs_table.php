<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('configs') ) {
			Schema::create('configs', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('logo', 191)->nullable();
				$table->string('favicon', 191)->nullable();
				$table->string('livetvicon')->nullable();
				$table->string('title', 191)->nullable();
				$table->string('w_email', 191)->nullable();
				$table->integer('verify_email');
				$table->integer('download')->nullable()->default(0);
				$table->integer('free_sub')->default(0);
				$table->integer('free_days')->nullable();
				$table->string('stripe_pub_key', 191)->nullable();
				$table->string('stripe_secret_key', 191)->nullable();
				$table->string('paypal_mar_email', 191)->nullable();
				$table->string('currency_code', 191)->nullable();
				$table->string('currency_symbol', 191);
				$table->string('invoice_add', 191)->nullable();
				$table->boolean('prime_main_slider')->default(1);
				$table->boolean('catlog');
				$table->boolean('withlogin');
				$table->boolean('prime_genre_slider')->default(1);
				$table->boolean('donation')->nullable();
				$table->string('donation_link', 100)->nullable();
				$table->boolean('prime_footer')->default(1);
				$table->boolean('prime_movie_single')->default(1);
				$table->text('terms_condition')->nullable();
				$table->text('privacy_pol')->nullable();
				$table->text('refund_pol')->nullable();
				$table->text('copyright', 65535)->nullable();
				$table->boolean('stripe_payment')->default(1);
				$table->boolean('paypal_payment')->default(1);
				$table->boolean('razorpay_payment')->default(1);
				$table->boolean('age_restriction')->nullable()->default(0);
				$table->boolean('payu_payment')->default(1);
				$table->boolean('bankdetails');
				$table->string('account_no', 111)->nullable();
				$table->string('branch', 111)->nullable();
				$table->string('account_name', 111)->nullable();
				$table->string('ifsc_code', 111)->nullable();
				$table->string('bank_name', 111)->nullable();
				$table->integer('paytm_payment')->unsigned()->nullable()->default(0);
				$table->boolean('paytm_test')->nullable();
				$table->boolean('preloader')->default(1);
				$table->boolean('fb_login');
				$table->boolean('gitlab_login');
				$table->boolean('google_login');
				$table->boolean('wel_eml')->nullable();
				$table->boolean('blog')->default(0);
				$table->boolean('is_playstore')->default(0);
				$table->boolean('is_appstore')->default(0);
				$table->string('playstore', 200)->nullable();
				$table->string('appstore', 200)->nullable();
				$table->string('color', 100)->nullable();
				$table->boolean('color_dark')->nullable();
				$table->boolean('user_rating')->default(0);
				$table->boolean('comments')->default(0);
				$table->boolean('braintree')->default(0);
				$table->boolean('paystack')->default(0);
				$table->boolean('remove_landing_page')->default(0);
				$table->boolean('coinpay')->default(0);
				$table->boolean('captcha')->default(0);
				$table->boolean('amazon_login')->default(0);
				$table->timestamps();
			});
		}
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('configs');
	}

}
