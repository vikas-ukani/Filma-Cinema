<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        
        $this->call(AdsensesTableSeeder::class);
        $this->call(AppConfigsTableSeeder::class);
        $this->call(AppSlidersTableSeeder::class);
        $this->call(AuthCustomizesTableSeeder::class);
        $this->call(ButtonsTableSeeder::class);
        $this->call(ChatSettingsTableSeeder::class);
        $this->call(ColorSchemesTableSeeder::class);
        $this->call(ConfigsTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(HomeSlidersTableSeeder::class);
        $this->call(LandingPagesTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(OauthClientsTableSeeder::class);
        $this->call(OauthPersonalAccessClientsTableSeeder::class);
        $this->call(PlayerSettingsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
        $this->call(SeosTableSeeder::class);
        $this->call(SocialIconsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AffilatesTableSeeder::class);
        $this->call(WalletSettingsTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RoleHasPermissionsTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(AppUiShortingsTableSeeder::class);
        $this->call(AllcountryTableSeeder::class);
    }
}
