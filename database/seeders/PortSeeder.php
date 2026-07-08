<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;

class PortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ports = [

            // =========================
            // MAJOR PORTS
            // =========================

            [
                'port_type_id' => 1,
                'state_id' => 31,
                'state_board_id' => 0,
                'port_name' => 'Chennai Port Authority',
                'port_code' => 'CHN',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 18,
                'state_board_id' => 0,
                'port_name' => 'Cochin Port Authority',
                'port_code' => 'COCHIN',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 12,
                'state_board_id' => 0,
                'port_name' => 'Deendayal Port Authority',
                'port_code' => 'DEENDAYAL',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 37,
                'state_board_id' => 0,
                'port_name' => 'Haldia Dock Complex',
                'port_code' => 'HALDIA',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 21,
                'state_board_id' => 0,
                'port_name' => 'Jawaharlal Nehru Port Authority',
                'port_code' => 'JNPT',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 31,
                'state_board_id' => 0,
                'port_name' => 'Kamarajar Port Authority',
                'port_code' => 'KAMARAJAR',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 11,
                'state_board_id' => 0,
                'port_name' => 'Mormugao Port Authority',
                'port_code' => 'MORMUGAO',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 21,
                'state_board_id' => 0,
                'port_name' => 'Mumbai Port Authority',
                'port_code' => 'MUMBAI',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 17,
                'state_board_id' => 0,
                'port_name' => 'New Mangalore Port Authority',
                'port_code' => 'MANGALORE',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 26,
                'state_board_id' => 0,
                'port_name' => 'Paradip Port Authority',
                'port_code' => 'PARADIP',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 37,
                'state_board_id' => 0,
                'port_name' => 'Syama Prasad Mookerjee Kolkata',
                'port_code' => 'KOLKATA',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 31,
                'state_board_id' => 0,
                'port_name' => 'V O Chidambaranar Port Authority',
                'port_code' => 'VOC',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 1,
                'state_id' => 1,
                'state_board_id' => 0,
                'port_name' => 'Visakhapatnam Port Authority',
                'port_code' => 'VIZAG',
                'port_data_code' => '0',
                'created_by' => 1,
            ],


            // =========================
            // ANDHRA PRADESH PORTS
            // =========================

            [
                'port_type_id' => 2,
                'state_id' => 1,
                'state_board_id' => 1,
                'port_name' => 'Gangavaram Port Limited',
                'port_code' => 'GANGAVARAM',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 1,
                'state_board_id' => 1,
                'port_name' => 'Kakinada Anchorage Port',
                'port_code' => 'KAKINADA_ANCH',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 1,
                'state_board_id' => 1,
                'port_name' => 'Kakinada Seaports Limited',
                'port_code' => 'KAKINADA_SEA',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 1,
                'state_board_id' => 1,
                'port_name' => 'Krishnapatnam Port Limited',
                'port_code' => 'KRISHNAPATNAM',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 1,
                'state_board_id' => 1,
                'port_name' => 'Ravva Port',
                'port_code' => 'RAVVA',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | Andaman Nicobar
            |--------------------------------------------------------------------------
            */
            [
                'port_type_id' => 2,
                'state_id' => 2,
                'state_board_id' => 2,
                'port_name' => 'Andaman Nicobar Island Ports',
                'port_code' => 'ANDAMAN',
                'port_data_code' => 'ANDAMAN',
            ],

            /*
            |--------------------------------------------------------------------------
            | Goa Ports
            |--------------------------------------------------------------------------
            */
            [
                'port_type_id' => 2,
                'state_id' => 11,
                'state_board_id' => 3,
                'port_name' => 'Panaji',
                'port_code' => 'PANAJI',
                'port_data_code' => 'PANAJI',
            ],


            // =========================
            // GUJARAT PORTS
            // =========================

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Alang',
                'port_code' => 'ALANG',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Bedi',
                'port_code' => 'BEDI',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Bedi Ebtsl Jetty',
                'port_code' => 'BEDI_EBTSL',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Bedi Sikka',
                'port_code' => 'BEDI_SIKKA',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Bhavnagar',
                'port_code' => 'BHAVNAGAR',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Dahej',
                'port_code' => 'DAHEJ',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Jafrabad',
                'port_code' => 'JAFRABAD',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Jafrabad Gujarat Pipavav Port',
                'port_code' => 'JAFRABAD_PIPAVAV',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Magdalla',
                'port_code' => 'MAGDALLA',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Magdalla Adani Hazira Port',
                'port_code' => 'MAGDALLA_ADANI_HAZIRA',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Magdalla Hazira Port Pvt Ltd',
                'port_code' => 'MAGDALLA_HAZIRA',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Mandvi Jakhau',
                'port_code' => 'MANDVI_JAKHAU',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Mandvi Mundra',
                'port_code' => 'MANDVI_MUNDRA',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Mandvi Mundra Port And Sez',
                'port_code' => 'MANDVI_MUNDRA_SEZ',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Navlakhi',
                'port_code' => 'NAVLAKHI',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Okha',
                'port_code' => 'OKHA',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Porbandar',
                'port_code' => 'PORBANDAR',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Veraval',
                'port_code' => 'VERAVAL',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            // [
            //     'port_type_id' => 2,
            //     'state_id' => 12,
            //     'state_board_id' => 4,
            //     'port_name' => 'Mundra Port',
            //     'port_code' => 'MUNDRA',
            //     'port_data_code' => '0',
            //     'created_by' => 1,
            // ],

            // [
            //     'port_type_id' => 2,
            //     'state_id' => 12,
            //     'state_board_id' => 4,
            //     'port_name' => 'Pipavav Port',
            //     'port_code' => 'PIPAVAV',
            //     'port_data_code' => '0',
            //     'created_by' => 1,
            // ],

            /*
            |--------------------------------------------------------------------------
            | Karnataka Ports
            |--------------------------------------------------------------------------
            */
            [
                'port_type_id' => 2,
                'state_id' => 17,
                'state_board_id' => 5,
                'port_name' => 'Honnavar',
                'port_code' => 'HONNAVAR',
                'port_data_code' => 'HONNAVAR',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 17,
                'state_board_id' => 5,
                'port_name' => 'Karwar',
                'port_code' => 'KARWAR',
                'port_data_code' => 'KARWAR',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 17,
                'state_board_id' => 5,
                'port_name' => 'Kundapur',
                'port_code' => 'KUNDAPUR',
                'port_data_code' => 'KUNDAPUR',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 17,
                'state_board_id' => 5,
                'port_name' => 'Malpe',
                'port_code' => 'MALPE',
                'port_data_code' => 'MALPE',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 17,
                'state_board_id' => 5,
                'port_name' => 'Old Mangalore',
                'port_code' => 'OLDMANGALORE',
                'port_data_code' => 'OLDMANGALORE',
            ],

            /*
            |--------------------------------------------------------------------------
            | Kerala Ports
            |--------------------------------------------------------------------------
            */
            [
                'port_type_id' => 2,
                'state_id' => 18,
                'state_board_id' => 6,
                'port_name' => 'Alappuzha',
                'port_code' => 'ALAPPUZHA',
                'port_data_code' => 'ALAPPUZHA',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 18,
                'state_board_id' => 6,
                'port_name' => 'Azhikkal',
                'port_code' => 'AZHIKKAL',
                'port_data_code' => 'AZHIKKAL',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 18,
                'state_board_id' => 6,
                'port_name' => 'Beypore',
                'port_code' => 'BEYPORE',
                'port_data_code' => 'BEYPORE',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 18,
                'state_board_id' => 6,
                'port_name' => 'Kollam',
                'port_code' => 'KOLLAM',
                'port_data_code' => 'KOLLAM',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 18,
                'state_board_id' => 6,
                'port_name' => 'Vizhinjam',
                'port_code' => 'VIZHINJAM',
                'port_data_code' => 'VIZHINJAM',
            ],

            /*
            |--------------------------------------------------------------------------
            | Maharashtra Ports
            |--------------------------------------------------------------------------
            */
            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Bankot Infrastructure Logistic',
                'port_code' => 'BANKOT',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Belapur MMB Port',
                'port_code' => 'BELAPUR',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Bhiwandi MMB Port',
                'port_code' => 'BHIWANDI',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Dabhol Konkan LNG',
                'port_code' => 'DABHOL',
                'port_data_code' => 'DABHOL',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Dahanu Adani Electricity',
                'port_code' => 'DAHANU_ADANI',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Dharamtar JSW DPPL',
                'port_code' => 'DHARAMTAR',
                'port_data_code' => 'DHARAMTAR',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Dharamtar PNP Maritime Service',
                'port_code' => 'DHARAMTAR_PNP',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Dighi Port Ltd',
                'port_code' => 'DIGHI',
                'port_data_code' => 'DIGHI',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Jaigad Angre Port Pvt Ltd',
                'port_code' => 'JAIGAD',
                'port_data_code' => 'JAIGAD',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Jaigad JSW Jaigarh Port Ltd',
                'port_code' => 'JSWJAIGAD',
                'port_data_code' => 'JSWJAIGAD',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Karanja Terminal And Logistics',
                'port_code' => 'KARANJA',
                'port_data_code' => 'KARANJA',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Kelshi Ashapura Minechem Ltd',
                'port_code' => 'KELSHI',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Ratnagiri Finolex Industries',
                'port_code' => 'RATFINOLEX',
                'port_data_code' => 'RATFINOLEX',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Ratnagiri Ultratech Cement Ltd',
                'port_code' => 'RATULTRA',
                'port_data_code' => 'RATULTRA',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Redi Port Ltd',
                'port_code' => 'REDI',
                'port_data_code' => 'REDI',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Revdanda Indoenergy Internation',
                'port_code' => 'REVDANDA_INDOENERGY',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Revdanda Jsw Steel Salav Ltd',
                'port_code' => 'REVDANDA',
                'port_data_code' => 'REVDANDA',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Trombay MMB Port',
                'port_code' => 'TROMBAY',
                'port_data_code' => 'TROMBAY',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Ulwa Belapur Ambuja Cements',
                'port_code' => 'ULWA_BELAPUR',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Vasai MMB Port',
                'port_code' => 'VASAI',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Vijaydurg MMB Port',
                'port_code' => 'VIJAYDURG',
                'port_data_code' => 'VIJAYDURG',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 21,
                'state_board_id' => 7,
                'port_name' => 'Yogayatan Ports Pvt Ltd',
                'port_code' => 'YOGAYATAN',
                'port_data_code' => '0',
                'created_by' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | Odisha Ports
            |--------------------------------------------------------------------------
            */
            [
                'port_type_id' => 2,
                'state_id' => 37,
                'state_board_id' => 8,
                'port_name' => 'Dhamara',
                'port_code' => 'DHAMARA',
                'port_data_code' => 'DHAMARA',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 37,
                'state_board_id' => 8,
                'port_name' => 'Gopalpur',
                'port_code' => 'GOPALPUR',
                'port_data_code' => 'GOPALPUR',
            ],


            /*
            |--------------------------------------------------------------------------
            | Puducherry Ports
            |--------------------------------------------------------------------------
            */
            [
                'port_type_id' => 2,
                'state_id' => 9,
                'state_board_id' => 9,
                'port_name' => 'Karaikal Port',
                'port_code' => 'KARAIKAL',
                'port_data_code' => 'KARAIKAL',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 9,
                'state_board_id' => 9,
                'port_name' => 'MTF Of Chemplast Sanmar',
                'port_code' => 'CHEMPLAST',
                'port_data_code' => 'CHEMPLAST',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 9,
                'state_board_id' => 9,
                'port_name' => 'Pondicherry Port',
                'port_code' => 'PONDICHERRY',
                'port_data_code' => 'PONDICHERRY',
            ],


            /*
            |--------------------------------------------------------------------------
            | Tamil Nadu Ports
            |--------------------------------------------------------------------------
            */
            [
                'port_type_id' => 2,
                'state_id' => 31,
                'state_board_id' => 10,
                'port_name' => 'Cuddalore Port',
                'port_code' => 'CUDDALORE',
                'port_data_code' => 'CUDDALORE',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 31,
                'state_board_id' => 10,
                'port_name' => 'Ennore Minor Port',
                'port_code' => 'ENNORE',
                'port_data_code' => 'ENNORE',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 31,
                'state_board_id' => 10,
                'port_name' => 'Kattupalli Port',
                'port_code' => 'KATTUPALLI',
                'port_data_code' => 'KATTUPALLI',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 31,
                'state_board_id' => 10,
                'port_name' => 'Kudankulam',
                'port_code' => 'KUDANKULAM',
                'port_data_code' => 'KUDANKULAM',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 31,
                'state_board_id' => 10,
                'port_name' => 'Nagapattinam Port',
                'port_code' => 'NAGAPATTINAM',
                'port_data_code' => 'NAGAPATTINAM',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 31,
                'state_board_id' => 10,
                'port_name' => 'Tirukkadaiyur Port',
                'port_code' => 'TIRUKKADAIYUR',
                'port_data_code' => 'TIRUKKADAIYUR',
            ],

            /*
            |--------------------------------------------------------------------------
            | Additional Gujarat Ports
            |--------------------------------------------------------------------------
            */
            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'Sachana',
                'port_code' => 'SACHANA',
                'port_data_code' => 'SCHN',
            ],

            [
                'port_type_id' => 2,
                'state_id' => 12,
                'state_board_id' => 4,
                'port_name' => 'SIMAR Port Private Limited',
                'port_code' => 'SIMAR',
                'port_data_code' => 'SPPL',
            ],

            /*
            |--------------------------------------------------------------------------
            | Lakshadweep
            |--------------------------------------------------------------------------
            */
            [
                'port_type_id' => 2,
                'state_id' => 13,
                'state_board_id' => 0,
                'port_name' => 'Lakshadweep',
                'port_code' => 'LD101',
                'port_data_code' => 'LD101',
            ],

            /*
            |--------------------------------------------------------------------------
            | Additional Tamil Nadu Ports Ports
            |--------------------------------------------------------------------------
            */

            [
                'port_type_id' => 2,
                'state_id' => 31,
                'state_board_id' => 10,
                'port_name' => 'Udangudi Port',
                'port_code' => 'UDANGUDI',
                'port_data_code' => '628 206',
                'created_by' => 1,
            ],

        ];

        foreach ($ports as $port) {
            Port::updateOrCreate(
                [
                    'port_code' => $port['port_code'],
                ],
                $port
            );
        }
    }
}
