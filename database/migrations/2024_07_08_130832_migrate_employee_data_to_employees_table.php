<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                if ($user->cod_staff) {
                    DB::table('employees')->insert([
                        'user_id' => $user->id,
                        'staff_code' => $user->cod_staff,
                        'first_name' => $user->first_name,
                        'last_name' => $user->name,
                        'department' => $user->departament,
                        'position' => $user->pozitie,
                        'card_number' => $user->numar_card,
                        'password' => $user->parola,
                        'joining_date' => $user->data_aderarii,
                        'gender' => $user->sex,
                        'marital_status' => $user->starea_civila,
                        'birth_date' => $user->data_nasterii,
                        'phone' => $user->telefon,
                        'identity_card' => $user->card_de_identitate,
                        'postal_code' => $user->cod_postal,
                        'nationality' => $user->nationalitate,
                        'education' => $user->educatie,
                        'profession' => $user->profesie,
                        'departure_date' => $user->data_plecarii,
                        'father_first_name' => $user->prenumele_tatalui,
                        'address' => $user->adresa,
                        'leave_balance' => $user->leave_balance
                    ]);
                }
            }

            Schema::table('users', function ($table) {
                $table->dropColumn([
                    'cod_staff',
                    'first_name',
                    'departament',
                    'pozitie',
                    'numar_card',
                    'parola',
                    'data_aderarii',
                    'sex',
                    'starea_civila',
                    'data_nasterii',
                    'telefon',
                    'card_de_identitate',
                    'functie',
                    'tip_personal',
                    'cod_postal',
                    'status_politic',
                    'rezidenta',
                    'nationalitate',
                    'educatie',
                    'data_absolvirii',
                    'scoala',
                    'profesie',
                    'data_plecarii',
                    'prenumele_tatalui',
                    'adresa',
                    'leave_balance',
                ]);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
